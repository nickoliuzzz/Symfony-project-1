var arrayOfData = [];
var table = $('#here_table');
var kek =1;
var neededInSort =  Number(-1);
var temppage = 0;
var maxPage = -1;
var stringFromSearched = "";
var goToPage = 0;
var checkBoxes = [];


appendData();
console.log(arrayOfData);
ajax();










function ajax() {
    $.ajax({
        url:"/ajax",
        method:"POST",
        data:{"arrayOfData":arrayOfData},
        dataType:"json",
        async:true,
        success:function (data) {


            table.children().remove();
            console.log("start");
             console.log(data);
            // console.log(data[0]);
            // console.log(data[1]);
            // console.log(data[2]);


            takeArray(data[1]);
            appendData();
            var left = $("<button id='keks' class='btn'>Prev</button>\n");
            var searched = $("<input type='text' name='searched' id='searched' class='form-control col-sm' style='width:25%'>");

            var right = $("<button id='keks1' class='btn'>Next</button>\n");
            table.append( searched);

            searched.val(stringFromSearched);



            searched.keyup(function(e){
                delay(function () {
                    goToPage = 0;
                    temppage = 0;
                    stringFromSearched = $('#searched').val();
                    appendData();
                    ajax();
                },350);

            });
            left.click(function(e){
                goToPage = -1;
                appendData();
                ajax();
            });
            right.click(function(e){
                goToPage = 1;
                appendData();
                ajax();
            });

            var delay = (function(){
                var timer = 0;
                return function(callback, ms){
                    clearTimeout (timer);
                    timer = setTimeout(callback, ms);
                };
            })();

           table.append("<table class='table'>");
            $("table").append("<thead><tr>" );

            for(var cell of data[0]){
                table.append();
                $button = $("<th type='button' class='cells col-3'>" + cell + "</th>");
                $button.on('click', sortButton);
                $("thead tr").append($button);
            }
            $("thead tr").append("<th>Select</th>");
            $("table").append("<tbody>" );
            data.shift();

            data.shift();

            $kek3=1;
            for(var sem of data) {
                //table.append("<tr>" );

                $("table tbody").append('<tr id="tr'+$kek3+'">');
                for (var elemen of sem) {

                    //  console.log(elemen["content"]);
                    var content = decodeURI(elemen["content"]).replace(/"/g, '');
                    $("#tr"+$kek3).append("<td>" + content + "</td>" );
                    //$("<td>" + content + "</td>").insertAfter("tr").last()
                    //console.log($("table tbody tr").last());
                }
                var index = data[data.indexOf(sem)][0]['content'];
                console.log(index);
                $checkBox = $("<td>" + "<input type='checkbox' id='ans'/>" + "</td>");
                $("#tr"+$kek3).append($checkBox);
                if(checkBoxes.indexOf(sem[0]["content"]) != -1)
                {
                    $checkBox.attr('checked', true);
                }
                $checkBox.on('click',function () {
                    var indexOfCheckbox = data[$('.ans').index(this)][0]["content"];
                    if(checkBoxes.indexOf(indexOfCheckbox) == -1)
                    {
                         checkBoxes.push(indexOfCheckbox);
                    }
                    else {
                        checkBoxes.splice(checkBoxes.indexOf(indexOfCheckbox), 1);
                    }
                });
                $kek3++;
                //table.append("</tr></tbody>" );
            }
            //table.append("</table>");
            $('#searched').focus();



            if(temppage > 0) {
                table.append(left);
            }
            if(temppage < maxPage) {
                table.append(right);
            }
        },
        error: function (response) {
        }
    });
}

function appendData() {
    arrayOfData = [];
    arrayOfData.push(neededInSort);
    arrayOfData.push(temppage);
    arrayOfData.push(goToPage);
    arrayOfData.push(maxPage);
    arrayOfData.push(stringFromSearched);
}

function takeArray(temp) {
  //  console.log(temp);
    neededInSort =temp[0];
    temppage = temp[1];
    goToPage = temp[2];
    maxPage = temp[3];
    stringFromSearched = temp[4];
}

function sortButton(e){
    goToPage = 0;
    temppage = 0;
    var neededInSort1 = $('.cells').index(this) + 1;
    if(neededInSort1 == neededInSort) {
        neededInSort = -neededInSort;
    }
    else neededInSort = neededInSort1;
    appendData();
    ajax();
}