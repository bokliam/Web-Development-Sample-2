import $ from 'jquery';
import {parse_json} from "./parse_json";


export const MovieInfo = function(sel) {
    var that = this;
    var obj = $(sel);
    if(obj.length === 0) {
        return;
    }

    var element = obj.get(0);

    var title = element.dataset.movie;
    var year = element.dataset.year;

    console.log(title);
    console.log(year);

    $.ajax({
        url: "https://api.themoviedb.org/3/search/movie",
        data: {api_key: "cc66c07ec6a1fc04db19115f1e51a9c1", query: title, year: year},
        method: "GET",
        dataType: "text",
        success: function(data) {
            var strings = data.split(",");
            var results = strings[1];
            var json = parse_json(data);
            if(results.charAt(results.length-1) != 0) {
                console.log("SUCCESS");
                console.log(json);

                // Get values
                var release = json.results[0].release_date;
                var average = json.results[0].vote_average;
                var count = json.results[0].vote_count;
                var plot = json.results[0].overview;
                var poster = "http://image.tmdb.org/t/p/w500" + json.results[0].poster_path;
                if (json.results[0].poster_path === null){
                    poster = null;
                }

                // Display tabs and info
                that.fillInfo(title, release, average, count, plot, poster);
                that.displayTab();
            }
            else{
                console.log("MOVIE NOT FOUND");
                $(document.getElementsByClassName("paper").item(0)).html("<p>No information" +
                    " available</p>");

            }
        },
        error: function(xhr, status, error) {
            // Error
            console.log(error);
            $(document.getElementsByClassName("paper").item(0)).html("<p>Unable to " +
                "communicate<br>with themoviedb.org</p>");
        }
    });
};

MovieInfo.prototype.fillInfo = function(title, release, avg, count, plot, posterURL) {

    // Info
    var html = "<ul><li><a href=''><img src='src/img/info.png'></a><div><p>Title: " + title + "<br><br>Released: " +
        release + "<br><br>Vote Average: " + avg + "<br><br>Vote Count: " + count + "</p></div></li>";

    // Overview
    html += "<li><a href=''><img src='src/img/plot.png'></a><div><p>" + plot + "</p></div></li>";

    // Poster
    if (posterURL != null){
        html += "<li><a href=''><img src='src/img/poster.png'></a><div><p class='poster'>" +
            "<img src=" + posterURL + "></p></div></li></ul>";
    }
    else{
        html += "</ul>";
    }

    $(document.getElementsByClassName("paper").item(0)).html(html);
};

MovieInfo.prototype.displayTab = function() {
    var that = this;

    // Display Info tab
    var page = $("ul > li:first-child");
    page.children("div").show();
    page.children("a").children("img").css("opacity", "1.0");

    $("ul > li > a").click(function(event) {
        event.preventDefault();

        // Using "that" doesnt work
        $(this).parent().siblings().children("div").fadeOut(1000);
        $(this).parent().siblings().children("a").children("img").css("opacity", "0.3");
        $(this).parent().children("div").fadeIn(1000);
        $(this).parent().children("a").children("img").css("opacity", "1.0");
    });

};
