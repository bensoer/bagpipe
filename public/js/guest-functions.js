

var rate = 3 * 1000;
window.setInterval(getCurrentlyPlaying, rate);
window.setTimeout(getCurrentlyPlaying,0);
window.setTimeout(getUpNext,0);
window.setInterval(getUpNext, rate);
var alreadyVoted = new Array();

var token = document.getElementById('session_token').innerHTML;
var playlist = new Playlist(5*1000, token );
playlist.updateArray();


function getCurrentlyPlaying(){
    var song = playlist.getNowPlaying();

    alert(song);

    var lbl = document.getElementById("label");
    lbl.innerHTML = "";

    var link = document.createElement("a");
    link.href= "https://www.youtube.com/watch?v=" + song.getID();
    link.target = "_blank";
    link.innerHTML = song.getName();

    lbl.appendChild(link);


/*
    var token = document.getElementById("session_token");
    var json = {"session_token": token.innerHTML};

    var data = JSON.stringify(json);
    var url = "/getCurrent";

    var post = $.post(url, {formData: data});

    post.done(function(result){
    if(result.success == false){

    }else{
    var lbl = document.getElementById("label");
    lbl.innerHTML = "";

    var link = document.createElement("a");
    link.href= "https://www.youtube.com/watch?v=" + result.id;
    link.target = "_blank";
    link.innerHTML = result.name;

    lbl.appendChild(link);
    }

    });
*/
}

function getUpNext(){
    var token = document.getElementById("session_token");
    var list = document.getElementById('list');
    var json = {"session_token": token.innerHTML};

    var data = JSON.stringify(json);
    var url = "/getUpNext";

    var post = $.post(url, {formData: data});

    //alert(token.innerHTML);

    post.done(function(result){
    if(result.success == false){
    location.reload();
    }else{
    var json = JSON.parse(result);
    //alert(json[0] + "\n" + json[1] + "\n" + json[2] + "\n" +  json.length);
    list.innerHTML = '';

    for(var i = 0; i < json[0].length ; i++){



    var listItem = document.createElement("li");
    listItem.className = "queue-item";

    var rowDiv = document.createElement("div");
    rowDiv.className = "row";

    var tntDiv = document.createElement("div");
    tntDiv.className = "col-lg-8";

    var mediaDiv = document.createElement("div");
    mediaDiv.className = "media";

    var thumbLink = document.createElement("a");
    thumbLink.className = "meda-left queue-thumb";
    thumbLink.target = "_blank";
    thumbLink.href="https://www.youtube.com/watch?v=" + json[1][i];

    // var thumbImg = document.createElement("img");
    // thumbImg.src = "http://img.youtube.com/vi/" + json[1][i] + "/hqdefault.jpg";
    // thumbImg.style.height = "50px";
    // thumbImg.style.width = "67px";
    // thumbImg.style.verticalAlign = "middle";

    //thumbLink.appendChild(thumbImg);

    var mediaBodyDiv = document.createElement("div");
    mediaBodyDiv.className = "media-body media-middle";
    mediaBodyDiv.target = "_blank";

    var mediaBodyLink = document.createElement("a");
    mediaBodyLink.href="https://www.youtube.com/watch?v=" + json[1][i];
    mediaBodyLink.target = "_blank";

    var mediaBodySpan = document.createElement("span");
    mediaBodySpan.className = "media-heading";
    var songName = document.createTextNode(json[0][i]);
    mediaBodySpan.appendChild(songName);

    mediaBodyLink.appendChild(mediaBodySpan);
    mediaBodyDiv.appendChild(mediaBodyLink);
    mediaDiv.appendChild(thumbLink);
    mediaDiv.appendChild(mediaBodyDiv);
    tntDiv.appendChild(mediaDiv);

    //votes and upvoting buttons

    var votingDiv = document.createElement("div");
    votingDiv.id = "voting";
    votingDiv.className = "btn-group pull-right";
    votingDiv.role = "group";
    votingDiv.setAttribute("aria-label","...");

    var votingBtn = document.createElement("button");
    votingBtn.type = "button";
    votingBtn.className = "btn btn-default votes";
    votingBtn.disabled = true;

    //votingBtn.setAttribute("meta-id", json[1][i]);

    //why the fuck do onclicks not work...
    //votingBtn.onclick = submitVote;

    /*function(){
     var token = document.getElementById("session_token");
     var videoId = this.id;

     alert("token: " + token.innerHTML + "\n videoId: " + videoId);
     };*/

    var votingSpan = document.createElement("span");
    votingSpan.className = "votes-number";
    var voteNum = document.createTextNode(json[2][i]);
    votingSpan.appendChild(voteNum);
    votingBtn.appendChild(votingSpan);

    var upVoteBtn = document.createElement("button");
    upVoteBtn.type = "button";
    upVoteBtn.className = "btn btn-default upvote";

    //upVoteBtn.setAttribute("meta-id", json[1][i]);
    upVoteBtn.value = json[1][i];

    upVoteBtn.onclick = submitVote;

    var upVoteSpan = document.createElement("span");
    upVoteSpan.className = "upvote-icon glyphicon glyphicon-chevron-up";
    upVoteSpan.setAttribute("aria-hidden", "true");
    upVoteBtn.appendChild(upVoteSpan);

    votingDiv.appendChild(votingBtn);
    votingDiv.appendChild(upVoteBtn);

    rowDiv.appendChild(tntDiv);
    rowDiv.appendChild(votingDiv);

    listItem.appendChild(rowDiv);

    document.getElementById("list").appendChild(listItem);
    }
    }
    });
    }


/** triggered when submitting a search for songs **/
$("#searchSong").submit(function(event){
    event.preventDefault();

    var $form = $( this ),
    data = $form.serialize(),
    url = "/searchSong";

    var posting = $.post( url, { formData: data } );

    posting.done(function(results){
    if(results.success){
    //window.alert("SUCCESSSSSS" + results.data);

    var list = document.getElementById('search_list');
    list.innerHTML = '';


    for (var i=0; i < results.data[1].length; i++){

    var link=document.createElement("a");
    var textnode=document.createTextNode(results.data[1][i]);
    link.className = "list-group-item";
    link.href="#";
    link.style.borderRadius = 0;
    link.id = results.data[3][i];

    /*var checkbox = document.createElement("input");
     checkbox.type="checkbox";
     //checkbox.innerHTML = results.data[1][i];
     checkbox.name = results.data[1][i];
     checkbox.value = results.data[3][i]; //store videoID in the checkbox value*/

    //checkbox.onClick future implementation allowing onclick adding of the song to the list

    /*link.appendChild(checkbox);*/
    link.appendChild(textnode);

    document.getElementById("search_list").appendChild(link);
    }

    var submitSelectedBtn = document.createElement("button");
    submitSelectedBtn.innerHTML = "Add To List";
    submitSelectedBtn.className = "btn btn-default";

    submitSelectedBtn.addEventListener("click", addToPlaylist);

    document.getElementById("search_list").appendChild(submitSelectedBtn);

    }else{
    window.alert("A Serious Error Has Occured. Please refresh the Host's page and try again");
    }
    });
    });

//don't bother updating this list, it will be updated on the next sync call
function addToPlaylist(){

    var results = document.getElementById("search_list").getElementsByTagName('A');
    var token = document.getElementById("session_token");

    var requested = new Array();

    for(var i = 0; i < results.length; i++){
    if(results[i].classList.contains("active")){
    requested.push(results[i].id);
    }
    }

    document.getElementById('search_list').innerHTML = ''; //clear the search list

    requested.push(token.innerHTML);
    var data = JSON.stringify(requested);
    var url = "/addToPlaylist";
    $.post(url, {formData: data });

    document.getElementById("search").value = "";
    }

function submitVote(){
    var token = document.getElementById("session_token");
    //var videoId = this.getAttribute("meta-id");
    var videoId = this.value;
    var button = videoId + "Button";
    var icon = videoId + "Icon";
    //alert("token: " + token.innerHTML + "\n" + "videoId : " + videoId);

    var isVoted = false;
    //alert("already voted length: " + alreadyVoted.length);
    for(var i = 0 ; i < alreadyVoted.length; i++){
    if(videoId === alreadyVoted[i]){
    alert("You have already voted for this song. You can not vote again");
    //TODO: Change upvote color on button click
    // document.getElementById(button).disabled = true;
    //document.getElementById(icon).style.color = "#449d44";
    //      document.getElementById("h5-FJsYj1ckButton").style.color = "#449d44";
    //this.disabled();
    //this.style.color = "#449d44";
    isVoted = true;
    }
    }

    if(!isVoted){
    //alert(voteStatus);
    //alert("token: " + token.innerHTML + "\n videoId: " + videoId);

    var json = {"session_token": token.innerHTML, "videoid": videoId};
    var data = JSON.stringify(json);
    var url = "./submitVote";
    var post = $.post(url, {formData: data});
    alreadyVoted.push(videoId);
    //TODO: Change upvote color on button click
    //document.getElementById(button).disabled = true;
    //document.getElementById(icon).style.color = "#449d44";
    //this.disabled = true;
    // this.style.color = "#449d44";
    getUpNext();
    }

    }


