/**
 * Render a html list of the available denominations.
 * @param {object} billetes
 */
function renderDenominaciones(billetes) {
  for (var i in billetes) {
    $('#billetes_list')
    .append('<li>$'+billetes[i].denominacion+'.00</li>');
  }
  return false;
}

/**
 * Fetch the list of denominations and renderst the.
 */
function getBilletes() {
  $.get('rest/list_cash', function(billetes){
    renderDenominaciones(billetes);
  });
  return false;
}

/**
 * Render a set of html elements that represents the amount of tickets and his denomination.
 * @param  {billetes} object
 */
function renderBilletes(billetes) {
  for (var i in billetes) {
    var billete_html = document.createElement("span");
    $(billete_html)
    .addClass('label label-success ticket')
    .html("$"+billetes[i].denominacion+".00  x " + billetes[i].cantidad);

    $('#result')
    .append(billete_html);
  }
}

/**
 * Send the desired cash amount and retreive the object that represents the cash machine operation.
 */
function getMoney() {
  $.post('rest/get_cash',{
    'amount':$('#amount').val()
  }, function(rs){
    $('#result').attr('class', 'col-md-12')
    .html(rs.result.msg)
    .addClass(rs.result.status);

    if (rs.result.status == 'SUCCESS') {
      renderBilletes(rs.billetes);
    }
  });
  return false;
}

$(function(){
  getBilletes();
  $('#keyboard .number').click(function(){
    $('#amount').val( $('#amount').val()+$(this).val() );
  });

  $('#keyboard #delete').click(function(){
    var current_amount = $('#amount').val();
    $('#amount').val( current_amount.substring(0, current_amount.length-1) );
  });
});