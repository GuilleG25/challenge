<template>
	<div class="time" :class="{ 'time-disabled': !hour.available }" @click="selectedTime">
		{{ hour.time }} {{ hour.period }} 
 		<span v-show="hour.available" class="label">Available</span>
		<span v-show="!hour.available" class="label">Occupied</span>
	</div>
</template>

<script>
	export default {
		name: 'timePicker',
		props: ['hour'],
		methods: {
			selectedTime() {
				if (this.hour.available) {
					return this.$emit('selectedTime', this.hour);
				}
			}
		}
	}
</script>