var arrayOfData = [];
var table = $('#here_table');
var  kek =1;
var  neededInSort = [-1];
ajax();
$(function() {
    //   jQuery('.cells').mousedown(console.log("kek"));

    $('#searched').keyup(ajax);
    $('#keks').click(ajax);
});

function ajax() {

    $.ajax({
        url:"/ajax",
        method:"POST",
        data:{"arrayOfData":arrayOfData},
        dataType:"json",
        async:true,
        success:function (data) {
            //  document.body.innerHTML = '<div id="here_table"></div>';
            //console.log(data);
            table.append('<button id="keks">Загрузить</button>\n' +
                '<input type=\'text\' name=\'searched\' id=\'searched\'>');

            //table.append("");

            table.append("<table>");
            table.append("<tr>" );

            //  var sem = data[0];


            for(var cell of data[0]){
                table.append();
                $button = $("<td type='button' class='cells'>" + cell + "</td>");
                $button.on('click', function(e) {
                   // console.log($('.cells').index(this));
                    neededInSort[0] = $('.cells').index(this);
                });
                table.append($button);
            }


            table.append("</tr>" );


            data.shift();

            for(var sem of data) {
                table.append("<tr>" );
                for (var elemen of sem) {

                    //  console.log(elemen["content"]);
                    var content = decodeURI(elemen["content"]);
                    content.replace(new RegExp('["]'), " ");
                    table.append("<td>" + content + "</dt>" );
                }
                table.append("</tr>" );
            }
            table.append("</table>");
        },
        error: function (response) {
            console.log('aaa');
            console.log(response);
        }
    });
}

