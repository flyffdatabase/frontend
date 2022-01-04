<script>
import { Line } from "vue-chartjs";

export default {
  extends: Line,
  props: ["data", "rangeLevel", "rangeMiddle", "options"],
  mounted() {
    let outData = [];
    for(let i = 0; i < this.data.length; i++) {
        outData.push({ level: i + 1, exp: this.data[i] });
    }
    outData = outData.filter((x) => x.exp > 0);
    this.renderChart( {
        labels: outData.map((x) => "Level: " + x.level),
        datasets: [{
            label: 'Exp in %',
            data: outData.map((x) => x.exp),
            fill: false,
            borderColor: '#17a2b8',
            tension: 0.1
        }]
    }, {});
  },
};
</script>