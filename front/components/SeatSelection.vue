<template>
  <div class="cinema">
    <div class="left-panel">
      <h3 class="section-title">Mapa de butaques</h3>
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
              <div class="seat" v-for="seat in row.seats" :key="seat.id" :class="{
                occupied: seat.status === 'Ocupada',
                selected: isSelected(seat),
                vip: seat.type === 'VIP' && seat.status === 'Disponible' && !isSelected(seat)
              }" @click="toggleSeatSelection(seat)">
                {{ seat.number }}
              </div>
            </div>
          </div>
        </div>
      </div>
      <p class="seat-selection" style="text-align: center;">
        {{ selectedSeats.length }}/10 butaques seleccionats
      </p>
    </div>

    <div class="right-panel">
      <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
      <div v-if="successMessage" class="success-message">{{ successMessage }}</div>
      <div class="user-form">
        <h3 class="form-title">Dades de Compra</h3>
        <input v-model="userData.name" type="text" placeholder="Nom" class="input-field" />
        <input v-model="userData.surname" type="text" placeholder="Cognom" class="input-field" />
        <input v-model="userData.email" type="email" placeholder="Adreça electrònica" class="input-field" />
        <input v-model="userData.phone" type="text" placeholder="Telèfon" class="input-field" />
      </div>
      <div class="buttons">
        <button @click="confirmPurchase" class="btn buy">Comprar Entrades</button>
        <button @click="clearSelection" class="btn clear">Netejar selecció</button>
      </div>
      <p class="purchase-summary">
        Has seleccionat <span id="count">{{ selectedSeats.length }}</span> butaques<br>per un preu total de
        <span id="total">{{ totalPrice }}</span> €
      </p>

      <div class="selected-seats-list">
        <h4>Seients seleccionats:</h4>
        <ul>
          <li v-for="(seat, index) in selectedSeats" :key="index" class="seat-item">
            <span class="seat-info">Fila {{ seat.row }} - Butaca {{ seat.number }}</span>
            <span class="price">
              {{ seatPrice(seat) }} €
            </span>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div v-if="isLoading" class="loading-overlay">
    <img src="../assests/loading.gif" alt="Càrregant..." class="loading-gif" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import CommunicationManager from '@/stores/communicationManager'

const isDiscountDay = ref(false)
const isLoading = ref(false);
const props = defineProps({
  sessionId: {
    type: String,
    required: true
  }
})
const rowLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L']
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
    const sessionResult = await CommunicationManager.getSessionDetails(props.sessionId)
    if (sessionResult.error) throw new Error(sessionResult.error)
    const session = sessionResult.session
    if (session && session.is_discount_day !== undefined) {
      isDiscountDay.value = Boolean(Number(session.is_discount_day))
    }
    const seatsResult = await CommunicationManager.getSeats(props.sessionId)
    if (seatsResult.error) throw new Error(seatsResult.error)
    const seatsData = seatsResult.seats
    seatsData.forEach(dbSeat => {
      const row = patiRows.value.find(r => r.letter === dbSeat.row)
      if (row) {
        const seat = row.seats.find(s => s.number === dbSeat.number)
        if (seat) {
          seat.status = dbSeat.status
          if (dbSeat.type) seat.type = dbSeat.type
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
    return isDiscountDay.value ? acc + (seat.row === 'F' ? 6 : 4) : acc + (seat.row === 'F' ? 8 : 6)
  }, 0)
})
const userData = ref({ name: '', surname: '', email: '', phone: '' })
const errorMessage = ref('')
const successMessage = ref('')
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
  isLoading.value = true;

  if (selectedSeats.value.length === 0) {
    showErrorMessage('Selecciona almenys una butaca.')
    isLoading.value = false; 
    return
  }
  if (!userData.value.name || !userData.value.surname || !userData.value.phone || !userData.value.email) {
    showErrorMessage('Si us plau, omple totes les dades personals.')
    isLoading.value = false;
    return
  }
  const ticketsResult = await CommunicationManager.getTickets(userData.value.email)
  if (ticketsResult.error) {
    showErrorMessage(ticketsResult.error)
    isLoading.value = false; 
    return
  }
  const existingTickets = ticketsResult.tickets.filter(ticket => String(ticket.session_id) === String(props.sessionId))
  if (existingTickets.length > 0) {
    showErrorMessage('Aquest correu ja té entrades per a aquesta sessió.')
    isLoading.value = false; 
    return
  }
  try {
    const purchaseData = {
      email: userData.value.email,
      session_id: props.sessionId,
      seats: selectedSeats.value,
      price: totalPrice.value
    }
    const result = await CommunicationManager.purchaseTickets(purchaseData)
    if (result.error) {
      showErrorMessage(result.error)
      isLoading.value = false; 
      return
    }
    showSuccessMessage('Entrades comprades correctament! Rebràs el tiquet per correu.')
    patiRows.value.forEach(row => {
      row.seats.forEach(seat => {
        if (isSelected(seat)) seat.status = 'Ocupada'
      })
    })
    selectedSeats.value = []
    isLoading.value = false;
  } catch (err) {
    showErrorMessage('S\'ha produït un error')
    isLoading.value = false;
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
function seatPrice(seat) {
  if (isDiscountDay.value) {
    return seat.row === 'F' ? 6 : 4;
  } else {
    return seat.row === 'F' ? 8 : 6;
  }
}
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css?family=Montserrat&display=swap");
@import url("https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css");

.selected-seats-list {
  margin-top: 1rem;
  font-size: 1rem;
  color: #333;
}

.selected-seats-list h4 {
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.selected-seats-list ul {
  list-style-type: none;
  padding-left: 0;
  margin: 0;
}

.selected-seats-list .seat-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
}

.selected-seats-list .seat-info {
  flex: 1;
}

.selected-seats-list .price {
  font-weight: bold;
  color: #007bff;
  text-align: right;
}

.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5); 
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999; 
}

.loading-gif {
  width: 50px; 
  height: auto;
}

.cinema {
  display: flex;
  flex-direction: row;
  gap: 2rem;
  margin: 2rem auto;
  max-width: 1200px;
  font-family: 'Roboto', sans-serif;
}

.left-panel {
  flex: 2;
  background-color: #fff;
  padding: 1rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.right-panel {
  flex: 1;
  background-color: #f7f7f7;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
}

.section-title {
  font-size: 1.8rem;
  font-weight: 700;
  margin-bottom: 1rem;
  text-align: center;
  color: #333;
}

.showcase {
  display: flex;
  justify-content: space-around;
  list-style-type: none;
  background: rgba(0, 0, 0, 0.05);
  padding: 0.5rem;
  border-radius: 5px;
  color: #777;
  margin-bottom: 1rem;
}

.showcase li {
  display: flex;
  align-items: center;
  gap: 0.3rem;
}

.map-info {
  font-size: 0.9rem;
  margin-bottom: 1rem;
  text-align: center;
}

.pati {
  width: 100%;
  margin-bottom: 1rem;
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
  gap: 5px;
  margin-bottom: 5px;
}

.row-label {
  width: 20px;
  font-weight: bold;
  text-align: center;
}

.row-seats {
  display: flex;
  gap: 3px;
}

.seat {
  background-color: #808080;
  height: 25px;
  width: 30px;
  margin: 3px;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 0.8rem;
  transition: transform 0.2s ease;
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
  margin: 1rem 0;
  font-size: 1rem;
}

p.text span {
  color: #28a745;
  font-weight: 600;
}

.user-form {
  margin-bottom: 1.5rem;
}

.form-title {
  font-size: 1.4rem;
  font-weight: 700;
  margin-bottom: 1rem;
  color: #333;
}

.input-field {
  display: block;
  width: 100%;
  padding: 10px;
  margin-bottom: 0.8rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 0.95rem;
}

.input-field:focus {
  outline: none;
  border-color: #e50914;
  box-shadow: 0 0 5px rgba(229, 9, 20, 0.3);
}

.buttons {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.btn {
  padding: 0.8rem 1.2rem;
  border-radius: 6px;
  color: #fff;
  font-weight: 600;
  cursor: pointer;
  outline: none;
  border: none;
  transition: background-color 0.3s ease;
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

.error-message {
  color: #dc3545;
  margin-bottom: 1rem;
  text-align: center;
}

.success-message {
  color: #28a745;
  margin-bottom: 1rem;
  text-align: center;
}

.purchase-summary {
  margin-top: 1rem;
  font-size: 1rem;
  text-align: center;
  color: #333;
}

.seat-selection {
  margin-top: 1rem;
  font-size: 1rem;
  text-align: center;
  color: #333;
}
</style>
