window.onload = function(){
    // Canvas width and height
    var width = 600;
    var height = 300;
    
    // Create a SVG canvas
    var thisCanvas = d3.select("svg")
        .attr("width", width)
        .attr("height", height)
        .attr("class", "svgCanvas");
    
    // We want some margin between the boundary of the canvas and the bar charts
    var margin = {top: 10, right: 20, bottom: 30, left: 50};
    width = width - margin.left - margin.right;
    height = height - margin.top - margin.bottom;
    
    // create the area to draw the bar chart
    // g is a SVG element to group multiple SVG elements
    var barChartArea = thisCanvas
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    
    // Set up a helpr function to divide x axis
    var x0 = d3.scaleBand()
        .rangeRound([0, width])
        .paddingInner(0.2); // padding between different groups
    
    // two property for each group
    var keys = ["income", "spend"];
    // Set up a helpr function to divide x axis **within** a group
    var x1 = d3.scaleBand()
        .padding(0.1); // padding between different properties within a group
    
    // helper function to calculate the height of each bar
    var y = d3.scaleLinear()
        .rangeRound([height, 0]);

        

    d3.csv("{{ url_for('static', filename="data/fourth.csv")}", function(data){
        console.log(data);
        
        // for differetn groups
        x0.domain(data.map(function(d) { return d['name']; }));
        // for properties within a group
        x1.domain(keys).rangeRound([0, x0.bandwidth()]);
        // for bar height
        y.domain([0, d3.max(data, function(d) {
            return d3.max(keys, function(key) {
                return parseInt(d[key]); 
            }); 
        })]);
        
        
        barChartArea.append("g")
            .selectAll("g")
            .data(data)
            .enter().append("g")
            // create groups
            .attr("transform", function(d) { 
              return "translate(" + x0(d["name"]) + ",0)"; 
              })
            .selectAll("rect")
            // pre-processing the data
            .data(function(d) {
                return keys.map(function(key) {
                    return {
                        key: key,
                        value: d[key],
                        name: d["name"]
                    };
                });
            })
            .enter().append("rect")
            // start drawing bars for each property and each group
            .attr("x", function(d) { 
                return x1(d["key"]); 
            })
            .attr("y", function(d) {
                return y(d["value"]); 
            })
            .attr("width", x1.bandwidth())
            .attr("height", function(d) { return height - y(d["value"]); })
            .attr("fill", function(d) {
                if(d["key"] === "income")
                {
                    return "lightgreen";
                }
                else if(d["key"] === "spend")
                {
                    return "red";
                }
            });
        
        // add x axis with ticks
        barChartArea.append("g")
            .attr("class", "axis")
            .attr("transform", "translate(0," + height + ")")
            .call(d3.axisBottom(x0));
        
        // add y axis with ticks
        barChartArea.append("g")
            .attr("class", "axis")
            .call(d3.axisLeft(y).ticks(null, "s"))
            .append("text")
            .attr("x", 2)
            .attr("y", y(y.ticks().pop()) + 0.5)
            .attr("dy", "0.32em");
    });
}