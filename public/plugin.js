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



           // data.shift();
            var left = $("<button id='keks'>Left</button>\n");
            var searched = $("<input type='text' name='searched' id='searched'>");
            var right = $("<button id='keks1'>Right</button>\n");
                   table.append( searched);
            if(temppage > 0) {
                table.append(left);
            }
            if(temppage < maxPage) {
                table.append(right);
            }



            searched.val(stringFromSearched);



            searched.keyup(function(e){
                goToPage = 0;
                temppage = 0;
                stringFromSearched = $('#searched').val();
                appendData();
                ajax();
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

            table.append("<table>");
            table.append("<tr>" );



            for(var cell of data[0]){
                table.append();
                $button = $("<td type='button' class='cells'>" + cell + "</td>");
                $button.on('click', function(e) {
                    goToPage = 0;
                    temppage = 0;
                    console.log($('.cells').index(this));
                    var neededInSort1 = $('.cells').index(this) + 1;
                    if(neededInSort1 == neededInSort) {
                        neededInSort = -neededInSort;
                    }
                    else neededInSort = neededInSort1;
                    appendData();
                    ajax();
                });
                table.append($button);
            }


            table.append("</tr>" );
            data.shift();

            data.shift();

            for(var sem of data) {
                table.append("<tr>" );
                for (var elemen of sem) {

                    //  console.log(elemen["content"]);
                    var content = decodeURI(elemen["content"]).replace(/"/g, '');
                    table.append("<td>" + content + "</dt>" );
                }
                var index = data[data.indexOf(sem)][0]['content'];
                console.log(index);
                $checkBox = $("<td>" + "<input type='checkbox' id='ans'/>" + "</td>");
                table.append($checkBox);
                $checkBox.on('click',function () {
                    console.log(checkBox.index(this));
                    console.log(index);
                });

                table.append("</tr>" );
            }
            table.append("</table>");
            $('#searched').focus();
        },
        error: function (response) {
            console.log('aaa');
            console.log(response);
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
    console.log(temp);
    neededInSort =temp[0];
    temppage = temp[1];
    goToPage = temp[2];
    maxPage = temp[3];
    stringFromSearched = temp[4];
}