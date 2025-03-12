<template>
  <div>
    <div class="options">
      <label>
        <input type="checkbox" v-model="vipActive" /> Activar VIP (Fila F)
      </label>
      <label>
        <input type="checkbox" v-model="isDiscountDay" /> Dia de l'espectador
      </label>
    </div>
    <div class="seat-grid">
      <div v-for="row in rows" :key="row" class="seat-row">
        <div
          v-for="seat in seatsPerRow"
          :key="seat"
          @click="toggleSeat(row, seat)"
          :class="getSeatClass(row, seat)"
        >
          {{ row }}{{ seat }}
        </div>
      </div>
    </div>
    <div class="summary">
      <p>Butacas seleccionades: {{ selectedSeats.join(', ') }}</p>
      <p>Total: €{{ totalPrice }}</p>
    </div>
    <button @click="confirmPurchase" class="buy-button">Comprar entradas</button>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

// Recibimos el ID de la sesión desde la propiedad de entrada
const props = defineProps({
  sessionId: {
    type: String,
    required: true
  }
})

const rows = ref(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'])
const seatsPerRow = ref(10)
const selectedSeats = ref([])
// Asientos ocupados cargados desde Laravel (se espera que el endpoint devuelva un JSON)
const occupiedSeats = ref([])

// Opciones de preus
const vipActive = ref(false)
const isDiscountDay = ref(false)

// Cargar los asientos ocupados desde Laravel
onMounted(async () => {
  try {
    const res = await fetch(`http://localhost:8000/api/sessions/${props.sessionId}/seats`)
    const data = await res.json()
    // Se espera que Laravel retorne algo como: { "occupiedSeats": ["A1", "B5", ...] }
    if (data.occupiedSeats) {
      occupiedSeats.value = data.occupiedSeats
    }
  } catch (error) {
    console.error('Error al cargar asientos ocupados:', error)
  }
})

function toggleSeat(row, seat) {
  const seatId = `${row}${seat}`
  // Si el asiento está ocupado, no se puede seleccionar
  if (occupiedSeats.value.includes(seatId)) {
    return
  }
  if (selectedSeats.value.includes(seatId)) {
    selectedSeats.value = selectedSeats.value.filter(s => s !== seatId)
  } else {
    if (selectedSeats.value.length < 10) {
      selectedSeats.value.push(seatId)
    } else {
      alert('Solo puedes seleccionar máximo 10 butaques por sesión.')
    }
  }
}

function getSeatClass(row, seat) {
  const seatId = `${row}${seat}`
  if (occupiedSeats.value.includes(seatId)) {
    return 'seat occupied'    // Vermell: ocupades
  } else if (selectedSeats.value.includes(seatId)) {
    return 'seat selected'    // Verd: seleccionades per l'usuari
  } else {
    return 'seat available'   // Gris: disponibles
  }
}

const totalPrice = computed(() => {
  return selectedSeats.value.reduce((total, seatId) => {
    const row = seatId.charAt(0)
    if (row === 'F' && vipActive.value) {
      return total + (isDiscountDay.value ? 6 : 8)
    }
    return total + (isDiscountDay.value ? 4 : 6)
  }, 0)
})

async function confirmPurchase() {
  if (selectedSeats.value.length === 0) {
    alert('Selecciona al menos una butaca.')
    return
  }
  
  // Construimos el payload en formato JSON para enviar a Laravel
  const payload = {
    seats: selectedSeats.value,
    vipActive: vipActive.value,
    isDiscountDay: isDiscountDay.value
  }
  
  try {
    const res = await fetch(`http://localhost:8000/api/sessions/${props.sessionId}/purchase`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      // Enviamos los datos en formato JSON, que Laravel debe guardar en la base de datos
      body: JSON.stringify(payload)
    })
    const data = await res.json()
    if (data.success) {
      // Se asume que Laravel devuelve { success: true } y guarda los asientos en un campo JSON
      occupiedSeats.value = [...occupiedSeats.value, ...selectedSeats.value]
      alert(`Compra realizada. Butaques: ${selectedSeats.value.join(', ')}. Total: €${totalPrice.value}`)
      selectedSeats.value = []
    } else {
      alert(data.message || 'Error en la compra. Revisa si ya tienes entradas para esta sesión.')
    }
  } catch (error) {
    console.error('Error al comprar entradas:', error)
    alert('Error al comprar las entradas.')
  }
}
</script>

<style scoped>
.options {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-bottom: 20px;
  font-size: 1.1rem;
}
.seat-grid {
  display: grid;
  grid-template-columns: repeat(10, 40px);
  gap: 5px;
  justify-content: center;
  margin-bottom: 20px;
}
.seat-row {
  display: contents;
}
.seat {
  padding: 10px;
  text-align: center;
  border-radius: 5px;
  user-select: none;
  transition: background-color 0.3s;
  cursor: pointer;
}
/* Gris: Disponibles */
.seat.available {
  background-color: #ccc;
  color: #000;
}
/* Verd: Seleccionades per l'usuari */
.seat.selected {
  background-color: #4caf50;
  color: white;
}
/* Vermell: Ocupades */
.seat.occupied {
  background-color: #f44336;
  color: white;
}
.summary {
  text-align: center;
  margin-bottom: 20px;
  font-size: 1.1rem;
}
.buy-button {
  background-color: #e50914;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  font-size: 1.1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}
.buy-button:hover {
  background-color: #b20710;
}
</style>
