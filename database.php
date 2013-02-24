<?php

   class Database {
		public $file;
		public $base;
		
		public function __construct($file){
			$this->file = $file;
		}
		
		public function connectDB(){
			$this->base = new SQLite3($this->file, 0666); 
		}
		
		public function getRankingReclamacoes($CNAESetor, $UF){
			$queryRanking = "SELECT strNomeFantasia, COUNT(*) AS qtd FROM Reclamacao WHERE strNomeFantasia IS NOT NULL AND strNomeFantasia <> 'NULL' ";
			
			if($CNAESetor != null){
				$queryRanking .= " AND CNAEPrincipal = '". $CNAESetor ."' ";
			}
			
			if($UF != null){
				$queryRanking .= " AND UF = '" . $UF . "' ";
			}
			
			$queryRanking .= " GROUP BY strNomeFantasia ORDER BY COUNT(*) DESC LIMIT 5 ";
			
			$results = $this->base->query($queryRanking);
			
			$ranking = array();
			$i = 0;
			
			while($arr = $results->fetchArray()){
				$ranking[$i]['NomeEmpresa'] = utf8_decode($arr['strNomeFantasia']);
				$ranking[$i]['qtd'] = $arr['qtd'];
				$i++;
		    }
			
			return $ranking;
		}
		
		public function getRankingNaoAtendidas($CNAESetor, $UF){
			$queryRanking = "SELECT strNomeFantasia, COUNT(*) AS qtd FROM Reclamacao WHERE strNomeFantasia IS NOT NULL AND 
																		  strNomeFantasia <> 'NULL' AND
																		  atendida = 'N' ";
			
			if($CNAESetor != null){
				$queryRanking .= " AND CNAEPrincipal = '". $CNAESetor ."' ";
			}
			
			if($UF != null){
				$queryRanking .= " AND UF = '" . $UF . "' ";
			}
			
			$queryRanking .= " GROUP BY strNomeFantasia ORDER BY COUNT(*) DESC LIMIT 5 ";
			
			$results = $this->base->query($queryRanking);
			
			$ranking = array();
			$i = 0;
			
			while($arr = $results->fetchArray()){
				$ranking[$i]['NomeEmpresa'] = utf8_decode($arr['strNomeFantasia']);
				$ranking[$i]['qtd'] = $arr['qtd'];
				$i++;
		    }
			
			return $ranking;
		}
		
		public function getQtdAtendidasNaoAtendidas($CNAESetor, $UF){
			$queryReclamacoes = "SELECT Atendida, COUNT(*) AS qtd FROM Reclamacao WHERE 1=1 ";
			
			if($CNAESetor != null){
				$queryReclamacoes .= " AND CNAEPrincipal = '". $CNAESetor ."' ";
			}
			
			if($UF != null){
				$queryReclamacoes .= " AND UF = '" . $UF . "' ";
			}
			
			$queryReclamacoes .= " GROUP BY Atendida ";
			
			$results = $this->base->query($queryReclamacoes);
			$qtdReclamacoes = array();
			
			while($arr = $results->fetchArray()){
				if($arr['Atendida'] == 'S') {
					$qtdReclamacoes['Atendidas'] = $arr['qtd'];
				}
				else if ($arr['Atendida'] == 'N'){
					$qtdReclamacoes['NaoAtendidas'] = $arr['qtd'];	
				}	
		    }
			
			return $qtdReclamacoes;
		}
		
		public function getProblemasRelatados($CNAESetor, $UF){
			$queryReclamacoes = "SELECT DescricaoProblema, COUNT(*) AS qtd FROM Reclamacao WHERE 1=1 ";
			
			if($CNAESetor != null){
				$queryReclamacoes .= " AND CNAEPrincipal = '". $CNAESetor ."' ";
			}
			
			if($UF != null){
				$queryReclamacoes .= " AND UF = '" . $UF . "' ";
			}
			
			$queryReclamacoes .= " GROUP BY DescricaoProblema ORDER BY COUNT(*) DESC LIMIT 5";
			
			$results = $this->base->query($queryReclamacoes);
			$qtdReclamacoes = array();
			
			while($arr = $results->fetchArray()){
				$qtdReclamacoes[utf8_encode($arr['DescricaoProblema'])] = 0;
			}
			
			$results = $this->base->query($queryReclamacoes);
			
			while($arr = $results->fetchArray()){
				$qtdReclamacoes[utf8_encode($arr['DescricaoProblema'])] += $arr['qtd'];
		    }
			
			return $qtdReclamacoes;
		}
   }

?>