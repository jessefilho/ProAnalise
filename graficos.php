<html>
<head>
<?php
   include("database.php");
   
   $database = new Database("procon2011.sqlite");
   $database->connectDB();
   
   $CNAEPrincipal = null;
   $UF = null;
   
   if(isset($_GET['CNAE'])){
		$CNAEPrincipal = $_GET['CNAE'];
   }
   
   if(isset($_GET['UF'])){
		$UF = $_GET['UF'];
   }
   
   $resultsReclamacoes = $database->getRankingReclamacoes($CNAEPrincipal, $UF);
   $resultsNaoAtendidas = $database->getRankingNaoAtendidas($CNAEPrincipal, $UF);
   
   //montando os arrays javascript:
   $arrayReclamacoesDados = "[";
   $arrayReclamacoesEmpresas = "[";
   $primeiro = true;
   $dadoAnterior = 0;
   foreach($resultsReclamacoes as $reclamacao){
		if(!$primeiro){
			$arrayReclamacoesDados .= ",";
			$arrayReclamacoesEmpresas .= ","; 
		}
		else{
			$primeiro = false;
		}
		
		if($reclamacao['qtd'] == $dadoAnterior){
			$reclamacao['qtd'] -= 0.1;
		}
		else{
			$dadoAnterior = $reclamacao['qtd'];
		}
		
		$arrayReclamacoesDados .= $reclamacao['qtd'];
		$arrayReclamacoesEmpresas .= "\"". $reclamacao['NomeEmpresa'] . "\"";	
   }
   $arrayReclamacoesDados .= "]";
   $arrayReclamacoesEmpresas .= "]";
   
   //montando os arrays javascript:
   $arrayNaoAtendidasDados = "[";
   $arrayNaoAtendidasEmpresas = "[";
   $primeiro = true;
   $dadoAnterior = 0;
   foreach($resultsNaoAtendidas as $reclamacao){
		if(!$primeiro){
			$arrayNaoAtendidasDados .= ",";
			$arrayNaoAtendidasEmpresas .= ",";
		}
		else{
			$primeiro = false;
		}
		
		if($reclamacao['qtd'] == $dadoAnterior){
			$reclamacao['qtd'] -= 0.1;
		}
		else{
			$dadoAnterior = $reclamacao['qtd'];
		}
		
		$arrayNaoAtendidasDados .= $reclamacao['qtd'];
		$arrayNaoAtendidasEmpresas .= "\"" . $reclamacao['NomeEmpresa'] . "\"";
   }
   $arrayNaoAtendidasDados .= "]";
   $arrayNaoAtendidasEmpresas .= "]";
  
?>

</head>

<title>APP Procon</title>
<!-- Linkando com o arquivo CSS -->
<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="icon" type="image/png" href="favicon.png">
		<style type="text/css">
			
			/* Gráficos */
			
			.arc path {
				  stroke: #fff;
				}
				
			.bar.positive {
				  fill: steelblue;
			}

			.bar.negative {
				  fill: brown;
			}

			.axis text {
				  fill:#000000;
				  font: 10px sans-serif;
			}

			.axis path,
			.axis line {
				  fill: none;
				  stroke: #000000;
				  shape-rendering: crispEdges;
			}
				
			.chart div {
				   font: 10px sans-serif;
				   background-color: steelblue;
				   text-align: right;
				   padding: 3px;
				   margin: 1px;
				   color: black;
			}
				
			.chart {
				  margin-left: 42px;
				  font: 10px sans-serif;
				  shape-rendering: crispEdges;
				  fill:black;
			}

			.chart rect {
				  stroke: black;
				  fill: steelblue;
			}

			.chart text.bar {
				  fill: black;
			}
			
		</style>
</head>

<body>
	<script src="http://d3js.org/d3.v3.min.js"></script>
	<div id="contentRight">			
			<!-- GRÁFICOS -->
			
			<div style="float: left; width: 300px;" id="barChart1">
				<h2 class="chartTitle">Empresas que mais recebem reclamações</h2>
				<script src="barChart.js"></script>
				<script><?="var dados = ". $arrayReclamacoesDados ."; var empresas = ". $arrayReclamacoesEmpresas ."; printBarChart(dados,empresas,'#barChart1');"?></script>
			</div>
			<div style="float: right; width: 300px;" id="barChart2">
				<h2 class="chartTitle">Empresas que menos atendem reclamações</h2>
				<script><?="var dados = ". $arrayNaoAtendidasDados ."; var empresas = ". $arrayNaoAtendidasEmpresas ."; printBarChart(dados,empresas,'#barChart2');"?></script>
			</div>
			
			<br />
			<div style="float: left; padding-top:50px">
				<div style="float: left; width: 300px;" id="pieChart">
					<h2 class="chartTitle">Reclamações<br /> Atendidas / Não Atendidas</h2>
					<script src="pieChart.js"></script>
				</div>
				
				<div style="float: right; width: 300px;" id="donutChart">
					<h2 class="chartTitle">Problemas mais <br />Relatados</h2>
					<script src="donutChart.js"></script>
				</div>
			</div>
			
		</div>

</body>

</html>