<template>
  <div class="cinema">
    <h3>Mapa de butaques</h3>
    <ul class="showcase">
      <li>
        <div class="seat"></div>
        <small>Gris: disponibles</small>
      </li>
      <li>
        <div class="seat selected"></div>
        <small>Verd: seleccionades per l'usuari</small>
      </li>
      <li>
        <div class="seat occupied"></div>
        <small>Vermell: ocupades</small>
      </li>
      <li>
        <div class="seat vip"></div>
        <small>Daurat: VIP disponibles</small>
      </li>
    </ul>

    <p class="map-info">
      L'usuari pot seleccionar fins a 10 butaques (màxim per sessió).
    </p>

    <div class="pati">
      <div class="pati-grid">
        <div class="pati-row" v-for="row in patiRows" :key="row.letter">
          <div class="row-label">{{ row.letter }}</div>
          <div class="row-seats">
            <div
              class="seat"
              v-for="seat in row.seats"
              :key="seat.id"
              :class="{
                occupied: seat.status === 'Ocupada',
                selected: isSelected(seat),
                vip: seat.type === 'VIP' && seat.status === 'Disponible' && !isSelected(seat)
              }"
              @click="toggleSeatSelection(seat)"
            >
              {{ seat.number }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <p class="text">
      Has seleccionat <span id="count">{{ selectedSeats.length }}</span> butaques per un preu total de 
      <span id="total">{{ totalPrice }}</span> €
    </p>

    <div v-if="errorMessage" class="text-red-500">{{ errorMessage }}</div>
    <div v-if="successMessage" class="text-green-500">{{ successMessage }}</div>
    
    <div class="user-form">
      <h3 class="text-lg font-bold mt-6">Dades personals</h3>
      <input v-model="userData.name" type="text" placeholder="Nom" class="input-field" />
      <input v-model="userData.surname" type="text" placeholder="Cognom" class="input-field" />
      <input v-model="userData.email" type="email" placeholder="Adreça electrònica" class="input-field" />
      <input v-model="userData.phone" type="text" placeholder="Telèfon" class="input-field" />
    </div>
    
    <div class="buttons">
      <button @click="confirmPurchase" class="btn buy">Comprar Entrades</button>
      <button @click="clearSelection" class="btn clear">Netejar selecció</button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
  sessionId: {
    type: String,
    required: true
  }
})

// Generem les files de la sala. Per cada seient, assignem també el camp "type".
// Si la fila és "F", i la sessió té VIP (aquí assumim que la fila F sempre és VIP),
 // el tipus serà "VIP"; en cas contrari, serà "Normal".
const rowLetters = ['A','B','C','D','E','F','G','H','I','J','K','L']
const patiRows = ref(rowLetters.map(letter => ({
  letter,
  seats: Array.from({ length: 10 }, (_, i) => ({
    id: `p-${letter}-${i + 1}`, 
    row: letter,
    number: i + 1,
    status: 'Disponible',
    type: letter === 'F' ? 'VIP' : 'Normal'
  }))
})))

onMounted(async () => {
  try {
    const res = await fetch(`http://localhost:8000/api/sessions/${props.sessionId}/seats`)
    if (!res.ok) throw new Error("Error al carregar els seients des del backend")
    const data = await res.json()
    data.data.forEach(dbSeat => {
      const row = patiRows.value.find(r => r.letter === dbSeat.row)
      if (row) {
        const seat = row.seats.find(s => s.number === dbSeat.number)
        if (seat) {
          seat.status = dbSeat.status
          if (dbSeat.type) {
            seat.type = dbSeat.type
          }
        }
      }
    })
  } catch (err) {
    console.error("Error carregant seients:", err)
  }
  
  const storedUser = localStorage.getItem('user')
  if (storedUser) {
    const parsedUser = JSON.parse(storedUser)
    userData.value.name = parsedUser.name || ''
    userData.value.surname = parsedUser.lastname || ''
    userData.value.email = parsedUser.email || ''
    userData.value.phone = parsedUser.phone || ''
  }
})

const selectedSeats = ref([])

const totalPrice = computed(() => {
  return selectedSeats.value.reduce((acc, seat) => {
    return acc + (seat.row === 'F' ? 8 : 6)
  }, 0)
})

const userData = ref({ name: '', surname: '', email: '', phone: '' })
const errorMessage = ref('')
const successMessage = ref('')

// Comprova si un seient ja està seleccionat
function isSelected(seat) {
  return selectedSeats.value.some(s => s.row === seat.row && s.number === seat.number)
}

function toggleSeatSelection(seat) {
  if (seat.status === 'Ocupada') return
  if (isSelected(seat)) {
    selectedSeats.value = selectedSeats.value.filter(s => !(s.row === seat.row && s.number === seat.number))
  } else if (selectedSeats.value.length < 10) {
    selectedSeats.value.push({ row: seat.row, number: seat.number })
  } else {
    showErrorMessage('Només pots seleccionar 10 butaques.')
  }
}

async function confirmPurchase() {
  if (selectedSeats.value.length === 0) {
    showErrorMessage('Selecciona almenys una butaca.')
    return
  }
  if (!userData.value.name || !userData.value.surname || !userData.value.phone) {
    showErrorMessage('Si us plau, omple totes les dades personals.')
    return
  }
  
  try {
    const res = await fetch(`http://localhost:8000/api/sessions/${props.sessionId}/seats`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ seats: selectedSeats.value })
    })
    const data = await res.json()
    if (!res.ok) {
      showErrorMessage(data.message || 'Error al reservar seients')
      return
    }
    showSuccessMessage('Entrades comprades correctament!')
    patiRows.value.forEach(row => {
      row.seats.forEach(seat => {
        if (isSelected(seat)) {
          seat.status = 'Ocupada'
        }
      })
    })
    selectedSeats.value = []
  } catch (err) {
    showErrorMessage('S\'ha produït un error')
    console.error(err)
  }
}

function clearSelection() {
  selectedSeats.value = []
  errorMessage.value = ''
}

function showErrorMessage(message) {
  errorMessage.value = message
  setTimeout(() => { errorMessage.value = '' }, 5000)
}

function showSuccessMessage(message) {
  successMessage.value = message
  setTimeout(() => { successMessage.value = '' }, 5000)
}
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css?family=Montserrat&display=swap");
@import url("https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css");

.cinema {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 2rem auto;
  max-width: 800px;
  font-family: 'Roboto', sans-serif;
}

.showcase {
  display: flex;
  justify-content: space-between;
  list-style-type: none;
  background: rgba(0,0,0,0.1);
  padding: 5px 10px;
  border-radius: 5px;
  color: #777;
  width: 100%;
  max-width: 650px;
  margin-bottom: 10px;
}

.showcase li {
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 10px;
}

.showcase li small {
  margin-left: 2px;
}

.map-info {
  font-size: 0.9rem;
  margin-bottom: 20px;
}

.pati {
  width: 100%;
  margin-bottom: 20px;
  display: flex;
  justify-content: center;
}

.pati-grid {
  display: flex;
  flex-direction: column;
  gap: 5px;
  align-items: center;
}

.pati-row {
  display: flex;
  align-items: center;
  margin-bottom: 5px;
}

.pati-row .row-label {
  width: 20px;
  font-weight: bold;
  margin-right: 5px;
  text-align: center;
}

.pati-row .row-seats {
  display: flex;
  gap: 3px;
}

.pati-row .seat {
  background-color: #808080;
  height: 25px;
  width: 25px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 0.7rem;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
}

.seat {
  background-color: #808080;
  height: 25px;
  width: 30px;
  margin: 5px;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 0.8rem;
}

.seat.selected {
  background-color: #28a745;
}

.seat.vip {
  background-color: gold !important;
}

.seat.occupied {
  background-color: #dc3545;
  color: #fff;
}

.seat:not(.occupied):hover {
  cursor: pointer;
  transform: scale(1.1);
}

p.text {
  margin: 40px 0;
  font-size: 1rem;
}

p.text span {
  color: #28a745;
  font-weight: 600;
}

.user-form {
  width: 100%;
  margin-top: 1rem;
}

.input-field {
  display: block;
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

.buttons {
  margin-top: 1rem;
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.btn {
  padding: 0.6rem 1rem;
  border-radius: 5px;
  color: #fff;
  font-weight: 600;
  cursor: pointer;
  outline: none;
  border: none;
}

.buy {
  background-color: #007bff;
}

.buy:hover {
  background-color: #0056b3;
}

.clear {
  background-color: #6c757d;
}

.clear:hover {
  background-color: #495057;
}

.img-container {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #fff;
}
</style>
