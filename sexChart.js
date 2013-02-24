function printAgeChart(){

	var data = [-30,-40,-50,35,50,70,20,25,-23];

	var margin = {top: 30, right: 10, bottom: 10, left: 10},
		width = 360 - margin.left - margin.right,
		height = 200 - margin.top - margin.bottom;

	var x0 = Math.max(-d3.min(data), d3.max(data));

	var x = d3.scale.linear()
		.domain([-x0, x0])
		.range([0, width])
		.nice();

	var y = d3.scale.ordinal()
		.domain(d3.range(data.length))
		.rangeRoundBands([0, height], .2);

	var xAxis = d3.svg.axis()
		.scale(x)
		.orient("top");

	var svg = d3.select("#sexChart").append("svg")
		.attr("width", width + margin.left + margin.right)
		.attr("height", height + margin.top + margin.bottom)
	  .append("g")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

	svg.selectAll(".bar")
		.data(data)
	  .enter().append("rect")
		.attr("class", function(d) { return d < 0 ? "bar negative" : "bar positive"; })
		.attr("x", function(d) { return x(Math.min(0, d)); })
		.attr("y", function(d, i) { return y(i); })
		.attr("width", function(d) { return Math.abs(x(d) - x(0)); })
		.attr("height", y.rangeBand());

	svg.append("g")
		.attr("class", "x axis")
		.call(xAxis);

	svg.append("g")
		.attr("class", "y axis")
	  .append("line")
		.attr("x1", x(0))
		.attr("x2", x(0))
		.attr("y1", 0)
		.attr("y2", height);
}

printAgeChart();