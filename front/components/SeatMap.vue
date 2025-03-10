<template>
    <div class="seat-map">
      <div
        v-for="row in rows"
        :key="row"
        class="seat-row"
      >
        <span class="row-label">{{ row }}</span>
        <div
          v-for="seat in seatsByRow(row)"
          :key="seat.number"
          :class="['seat', seat.status.toLowerCase(), { selected: isSelected(seat) }]"
          @click="toggleSeat(seat)"
        >
          {{ seat.number }}
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      seats: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        selected: []
      }
    },
    computed: {
      rows() {
        return 'ABCDEFGHIJKL'.split('')
      }
    },
    methods: {
      seatsByRow(row) {
        return this.seats.filter(seat => seat.row === row)
      },
      isSelected(seat) {
        return this.selected.some(s => s.id === seat.id)
      },
      toggleSeat(seat) {
        // Només es poden seleccionar butaques disponibles
        if (seat.status !== 'Disponible') return
  
        if (this.isSelected(seat)) {
          this.selected = this.selected.filter(s => s.id !== seat.id)
        } else {
          if (this.selected.length >= 10) {
            alert('Màxim 10 butaques per sessió.')
            return
          }
          this.selected.push(seat)
        }
        // Emetem la selecció actualitzada
        this.$emit('update-selection', this.selected)
      }
    }
  }
  </script>
  
  <style scoped>
  .seat-map {
    display: flex;
    flex-direction: column;
    margin: 20px 0;
  }
  .seat-row {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
  }
  .row-label {
    width: 20px;
    font-weight: bold;
  }
  .seat {
    width: 30px;
    height: 30px;
    margin: 0 3px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 1px solid #ccc;
    border-radius: 3px;
  }
  .seat.disponible {
    background-color: #ccc;
  }
  .seat.ocupada {
    background-color: red;
    cursor: not-allowed;
  }
  .seat.selected {
    background-color: green;
  }
  </style>
  