<?php

class DtaTbleOut {

	    function __construct($TbleName, $tableValues, $outHead='', $search=false, $sortord=" 0, 'asc' ", $drpdwn1='50, 75, 100, -1', $drpdwn2='50, 75, 100, "All"', $footer='YES', $screenOut='Y', $SearchTxt='', $paging=true, $search2=false) {
    		$this->TblNm     = $TbleName;
			$this->tableData = $tableValues;
			$this->drp1      = $drpdwn1;
			$this->drp2      = $drpdwn2;
			$this->outsort   = $sortord;
			$this->OutErrFlg = FALSE;
			$this->search    = $search;
			$this->search2   = ($search===true ? false : $search2);
			$this->footer    = $footer;
			$this->SearchTxt = $SearchTxt;
			$this->paging    = ($paging===true ? 'true' : 'false');
			if ((!isset($this->tableData)) || (!is_array($this->tableData))) {
				$this->OutErr    = "Error - No array() data sent to BPSDtaTbleOut class.";
				$this->OutErrFlg = TRUE;
				return;
			}

			if (is_array($outHead)) {
                $this->headers = $outHead;
            } else {
            	$this->setHeaders();
            }

            if ($screenOut == 'Y') {
            	$this->tableOutput();
            }
        }

	    function setHeaders() {
        	$counter = 0;
        	foreach ($this->tableData[0] as $fields=>$data) {
				$this->headers[$counter++] = $fields;
			}
		}

		function tableOutput($arrayData='') {

            if (is_array($arrayData)) {
                $this->tableData = $arrayData;
            }

			echo "<center>\n<table id=\"" . $this->TblNm . "\" class=\"tablesorter\" border=\"1\" cellpadding=\"1\" cellspacing=\"1\" >\n<thead>\n\t<tr>\n";
			foreach ($this->headers as $key => $header) {
				echo "\t\t<th rowspan=\"1\" style=\"vertical-align: bottom; text-align: center;\"><b>" . $header . "</b></th>\n";
			}
			echo "\t</tr>\n</thead>\n";

			if ($this->footer == 'YES') {
				echo "\t<tfoot>\n\t<tr>\n";
				foreach ($this->headers as $key => $header) {
					echo "\t\t<th rowspan=\"1\" style=\"vertical-align: top; text-align: center;\"><b>" . $header . "</b></th>\n";
				}
				echo "\t</tr>\n</tfoot>\n";
			}
			echo "<tbody>\n";
			foreach ($this->tableData as $key => $row) {
				echo "<tr>";
				foreach ($row as $key2 => $column) {
					$column = (is_array($column) ? (isset($column[0]) ? $column[0] : '') : $column ) ;
					if (trim($column) != '') {
						/* try ctype_digit instead of is_numeric */
						if ((is_numeric(trim($column))) && ((strtoupper($key2) != 'YR') && (strtoupper($key2) != 'YEAR') && (strtoupper($key2) != 'PHYS_FILE_ID') && (strtoupper($key2) != 'COLLECTION_ID') && (strtoupper($key2) != 'LOCATOR')  )  ) {
							echo "<td>" . number_format(trim($column)). "</td>";
						} elseif ((strpos(strtoupper($key2), 'DESCRIPTION') !== false ) || (strpos(strtoupper($key2), 'DESC') !== false ) || (strpos(strtoupper($key2), 'CLIENT') !== false )) {
								$removing = array(' - ', '10.8', '10.85', '10.8X', '2x', '20.1.1', '20.3', '3.2.2', '3.4.4', '3.4.5', 'AWD345', '3x', 'AWD 10', 'AWD 10.8X', 'AWD 10.X', 'AWD 2019 R2', 'AWD10', 'AWD10.85', 'AWD10.8X', 'AWD10X', 'AWD345', 'DPC', 'UCTION');
								$cleaned  = trim(str_replace($removing, '', trim(strtoupper($column))));
								$cleaned  =  (substr($cleaned, -4, 4) == 'PROD' ? substr($cleaned, 0, -4) : $cleaned) ;
								echo "<td>".$cleaned."</td>";
						} else {
							echo "<td>" . trim($column) . "</td>";
						}

					} else {
						echo "<td>&nbsp;</td>";
					}
				}
				echo "</tr>\n\n";
			}


			$addTextSetup =  ($this->search2   == true ? "\n\t\t\t// Setup - add a text input to each footer cell\n\t\t\t$('#".$this->TblNm." tfoot th').each( function () {\n\t\t\t\tvar title = $(this).text();\n\t\t\t\t$(this).html( '<input type=\"text\" placeholder=\"Search '+title+'\" />' );\n\t\t\t} );\n" : "");
			echo "</tbody>\n</table>\n</center>\n\t\n<script>\n\t\t\$(document).ready(function() {". $addTextSetup ."\n\t\t\tvar table = \$('#".$this->TblNm."').DataTable({\n\t\t\t\t\"aLengthMenu\": [\n\t\t\t\t\t[".$this->drp1."],\n\t\t\t\t\t[".$this->drp2."]\n\t\t\t\t],\n\t\t\t\t\"scrollCollapse\": true,\n\t\t\t\t\"jQueryUI\":       true,\n\t\t\t\t\"fixedHeader\":    true,\n\t\t\t\t\"paging\":         ".$this->paging.",\n\t\t\t\t\"autoWidth\":      false,\n\t\t\t\t\"order\": [".$this->outsort."]";

			echo ($this->SearchTxt != ''   ? ",\n\t\t\t\"search\": { \"search\": \"".$this->SearchTxt."\" }\n\t\t\t} );" : ($this->search2 == true ? "" : "\n\t\t\t}  );") );
			echo ($this->search    == true ? "\n\n\t\t\$(\"#".$this->TblNm." tfoot th\").each( function ( i ) {\n\t\t\tvar select = \$('<select><option value=\"\"></option></select>')\n\t\t\t\t.appendTo( \$(this).empty() )\n\t\t\t\t.on( 'change', function () {\n\t\t\t\t\tvar val = \$(this).val();\n\n\t\t\t\t\t\ttable.column( i )\n\t\t\t\t\t\t\t.search( val ? '^'+\$(this).val()+'\$' : val, true, false )\n\t\t\t\t\t\t\t.draw();\n\t\t\t\t} );\n\n\t\t\t\ttable.column( i ).data().unique().sort().each( function ( d, j ) {\n\t\t\t\t\tselect.append( '<option value=\"'+d+'\">'+d+'</option>' )\n\t\t\t\t} );\n\t\t\t} );".( $this->search2 == true ? " " : "\n\t\t} );\n</script>\n") : ($this->search2 == true ? "" :  "\n\t\t} );\n</script>\n") );
			echo ($this->search2   == true ? ",\n\t\t\n\t\t\t\tinitComplete: function () {\n\t\t\t\t\t// Apply the search\n\t\t\t\t\tthis.api().columns().every( function () {\n\t\t\t\t\t\tvar that = this;\n\t\t\t\t\t\t$( 'input', this.footer() ).on( 'keyup change clear', function () {\n\t\t\t\t\t\t\tif ( that.search() !== this.value ) {\n\t\t\t\t\t\t\t\tthat\n\t\t\t\t\t\t\t\t\t.search( this.value )\n\t\t\t\t\t\t\t\t\t.draw();\n\t\t\t\t\t\t\t}\n\t\t\t\t\t\t} );\n\t\t\t\t\t} );\n\t\t\t\t}\n\t\t\t} );\n\t\t} );\n</script>\n" : "" );

		}

		function csvOutput($filename, $arrayData='', $csvHead='', $delimiter=',', $field_delimit='"') {
			$this->fp1 = fopen($filename, 'w');
            if (is_array($arrayData)) {
                $this->tableData = $arrayData;
            }
            if (is_array($csvHead)) {
                $this->headers = $csvHead;
            } else {
            	$this->setHeaders();
            }

			fputcsv($this->fp1, $this->headers, $delimiter, $field_delimit);
			foreach ($this->tableData as $fields) {
			    fputcsv($this->fp1, $fields, $delimiter, $field_delimit);
			}

			fclose($this->fp1);

		}

		function csvOutput2level($filename, $arrayData='', $csvHead='', $delimiter=',', $field_delimit='"') {
			$this->fp1 = fopen($filename, 'w');
            if (is_array($arrayData)) {
                $this->tableData = $arrayData;
            }
            if (is_array($csvHead)) {
                $this->headers = $csvHead;
            } else {
            	$this->setHeaders();
            }

			fputcsv($this->fp1, $this->headers, $delimiter, $field_delimit);
			foreach ($this->tableData as $level1) {
				if (is_array($level1)) {
					foreach ($level1 as $fields){
						fputcsv($this->fp1, $fields, $delimiter, $field_delimit);
					}
				}
			}

			fclose($this->fp1);

		}

		function jsonOutput($filename, $arrayData='') {
			$fp2 = fopen($filename, 'w');
            if (is_array($arrayData)) {
            	$this->tableData = $arrayData;
            }

            fwrite($fp2, json_encode($this->tableData));

			fclose($fp2);
		}

		function htmltable2csv($csvOut, $tbldta, $save='Y') {
			$csv       = array();
			preg_match('/<table(>| [^>]*>)(.*?)<\/table( |>)/is',$tbldta,$b);
			$table   = $b[2];
			preg_match_all('/<tr(>| [^>]*>)(.*?)<\/tr( |>)/is',$tbldta,$b);
			$rows    = $b[2];
			foreach ($rows as $row) {
			    //cycle through each row
			    if(preg_match('/<th(>| [^>]*>)(.*?)<\/th( |>)/is',$row)) {
			        //match for table headers
			        preg_match_all('/<th(>| [^>]*>)(.*?)<\/th( |>)/is',$row,$b);
			        $csv[] = strip_tags(implode(',',$b[2]));
			    } elseif(preg_match('/<td(>| [^>]*>)(.*?)<\/td( |>)/is',$row)) {
			        //match for table cells
			        preg_match_all('/<td(>| [^>]*>)(.*?)<\/td( |>)/is',$row,$b);
			        $bOut = str_replace(',', '', $b[2]);
			        $csv[] = strip_tags(implode(',',$bOut));
			    }
			}
			$csvData = implode("\n", $csv);
			if ($save == 'Y') {
				$fp3 = fopen($csvOut, 'w');
				fputs($fp3, $csvData);
				fclose($fp3);
			} else {
				return $csvData;
			}
		}

		function htmltable2array ($htmlTableData) {
			$DOM = new DOMDocument();
			@$DOM->loadHTML($htmlTableData);  /* Using '@' to suppress the warning messages that come from useing these functions. */

			$Header = $DOM->getElementsByTagName('th');
			$Detail = $DOM->getElementsByTagName('td');

		    //#Get header name of the table
			foreach($Header as $NodeHeader)
			{
				$aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
			}

			//#Get row data/detail table without header name as key
			$i = $j = 0;
			$aDataTableDetailHTML = $aTempData = array();
			foreach($Detail as $sNodeDetail)
			{
				$aDataTableDetailHTML[$j][] = trim($sNodeDetail->textContent);
				$i = $i + 1;
				$j = $i % count($aDataTableHeaderHTML) == 0 ? $j + 1 : $j;
			}

			//#Get row data/detail table with header name as key and outer array index as row number
			for($i = 0; $i < count($aDataTableDetailHTML); $i++)
			{
				for($j = 0; $j < count($aDataTableHeaderHTML); $j++)
				{
					$aTempData[$i][$aDataTableHeaderHTML[$j]] = $aDataTableDetailHTML[$i][$j];
				}
			}
			$aDataTableDetailHTML = $aTempData; unset($aTempData);

			return $aDataTableDetailHTML;

		}
}
?>
