
function bondingCheckBoxChanged(){

	var indix = this.name.substring(11);
	if(this.checked){ // retrieve nb
		$("div[name='bonding-vlan-"+indix+"']").after("<div id='vlan-div-"+indix+"'  class='form-group'>\
			<label for='vlan-"+indix+"'>VLAN ID</label>\
			<input type='text' class='form-control' name='vlan-"+indix+"' placeholder='Numero de VLAN' required aria-required='true' pattern='(\\d)+' title='Ex: 123'></div>");
	}else{
		$("#vlan-div-"+indix).remove();
	}
};

function generateNetworkEntry(indix){

	return "<div name='network-def-"+indix+"'><div class='page-header'><h1><small>Carte réseau "+indix+"</small></h1></div><div class='checkbox' name='bonding-vlan-"+indix+"'>\
	<label><input name='bonding-cb-"+indix+"' type='checkbox'>Bonding</label></div><div class='form-group'><label for='macAddress-"+indix+"'>MAC</label><input type='text' class='form-control' name='macAddress-"+indix+"' placeholder='Adresse MAC' required aria-required='true' pattern='(\\d|[a-fA-F]){2}:(\\d|[a-fA-F]){2}:(\\d|[a-fA-F]){2}:(\\d|[a-fA-F]){2}:(\\d|[a-fA-F]){2}:(\\d|[a-fA-F]){2}' title='XX:XX:XX:XX:XX:XX'>\
	</div><div class='form-group'><label for='ipAddress-"+indix+"'>IP</label><input type='text' class='form-control' name='ipAddress-"+indix+"' placeholder='Adresse IP' required aria-required='true' pattern='\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}' title='Ex: 192.168.0.0'>\
	</div><div class='form-group'><label for='gateway-"+indix+"'>Passerelle par défaut</label><input type='text' class='form-control' name='gateway-"+indix+"' placeholder='IP de passerelle' required aria-required='true' pattern='\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\/\\d{1,2}:\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}' title='Ex: 192.168.0.24/24:192.168.0.1'></div></div>";

};

function updateNetworkNumbers(nb){
// Select the current number of network
do
{
	var nbNetworks = $("div[name|='network-def']").length;

	// If less than new => add
	if(nbNetworks < nb){
		var indix = nbNetworks+1;
		var out = generateNetworkEntry(indix);

		$("div[name|='network-def']:last").after(out);

		var script = document.createElement( "script" );
		script.type = "text/javascript";
		script.src = "addEvent.js";
		$("body").append(script);

	}
	if(nbNetworks > nb){
		$("div[name|='network-def']:last").remove();
	}
}while(nbNetworks != nb);
};    

$("#network-number").change(function(){
	updateNetworkNumbers($(this).val());
});

$("#form-main").submit(function( event ) {

	// Stop form from submitting normally
	event.preventDefault();

	// Get some values from elements on the page:
	var $form = $( this );
	
	// Build line : 
	var line = $form.find( "input[name='machineName']" ).val()+";";
	line += $form.find( "input[name='domainName']" ).val()+";";
	line += $form.find( "input[name='kickstartProfile']" ).val()+";";

	for(var i=1; i<=$("#network-number").val(); i++){
		var bonding = $form.find( "input[name='bonding-cb-"+(i)+"']" ).is(":checked");
		
		var netLine = bonding? ("bond0~"+$form.find( "input[name='vlan-"+(i)+"']" ).val()+"~") : "nobond~novlan~";
		netLine += "eth"+(i-1)+"~"+$form.find( "input[name='macAddress-"+(i)+"']" ).val()+"~";
		netLine += $form.find( "input[name='ipAddress-"+(i)+"']" ).val()+"~";

		netLine += ($form.find( "input[name='gateway-"+(i)+"']" ).val().length == 0) ? "0.0.0.0/0" : $form.find( "input[name='gateway-"+(i)+"']" ).val();

		netLine += (i == $("#network-number").val()) ? ";" : ",";

		line += netLine;
	}

	url = $form.attr( "action" );

	confFile = $("#conf-file-selector").val();
	var posting = $.post( url, { machine: line, conf : confFile }, "json" );

	posting.done(function( data ) {

		$form.find("button[type='submit']").after('<div class="alert alert-success" role="alert">\
        <strong>La machine '+$form.find( "input[name='machineName']" ).val()+' a été ajoutée au fichier de configuration spacewalk.\
      </div>');

	});

	posting.error(function( data ) {
		$form.find("button[type='submit']").after('<div class="alert alert-danger" role="alert">\
        <strong>La machine '+$form.find( "input[name='machineName']" ).val()+' n\'a pas été ajoutée au fichier de configuration spacewalk : '+data.responseText+'\
      </div>');

	});
});