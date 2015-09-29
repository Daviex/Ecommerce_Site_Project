<link rel="stylesheet" href="./asset/styles/orders.css" type="text/css">

<?php
	include("../include/BasicLib.php");

    $SQL = "SELECT COUNT(*) as CountAcq FROM Acquisti LEFT JOIN Ordini ON acquisti.IDOrdine = Ordini.ID WHERE Ordini.IDUser = '$idSession' GROUP BY Ordini.ID ORDER BY Ordini.Data_Acq DESC";

    $countAcq = array();

    $result = $conn->query($SQL);

    while($row = $result->fetch_assoc())
        $countAcq[] = $row['CountAcq'];

    $SQL = "SELECT Orders.Data_Acq, Acq.Quantita AS Quantita, Acq.Costo, Prod.Nome AS NomeProd, Stat.Nome AS NomeStato, User.Nome, User.Cognome, Prod.IMG as Image, Prod.ID as IDProd, Cat.Nome as catNome, Sped.Data_Arr as dataArr, Sped.Data_Part as dataPart, Orders.ID as OID
            FROM (((((acquisti AS Acq
                  LEFT JOIN ordini AS Orders ON Acq.IDOrdine = Orders.ID)
                  JOIN utenti as User ON Orders.IDUser = User.ID)
                  JOIN stati AS Stat ON Acq.IDStato = Stat.ID)
                  JOIN prodotti AS Prod ON Acq.IDProd = Prod.ID)
                  JOIN categorie AS Cat ON Prod.IDCat = Cat.ID)
                  LEFT JOIN spedizioni AS Sped ON Acq.IDSped = Sped.ID
            WHERE Orders.IDUser = '$idSession'
            ORDER BY Orders.Data_Acq DESC";
?>

<div id="ordersPage">
    <div class="title">
        I miei ordini
    </div>
    <div class="back">
        <button class="backButtonStyle" onclick="myProfile()">Indietro</button>
    </div>

    <!-- Questo mi imposterà per bene la round box attorno ai float! -->
    <div style="clear: both;"></div>

<?php
    $result = $conn->query($SQL) or die($conn->error);

    $currentOID = 0;
    $index = 0;

    if(count($countAcq) > 0)
        $CountOrder = $countAcq[$index];
    else
        $CountOrder = 0;

    $inOrder = false;

    if($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $NomeProd = $row['NomeProd'];
            $IDProd = $row['IDProd'];
            $Costo_Tot = $row['Costo'];
            $Quantita = $row['Quantita'];
            $Data_Acq = MySqlDateTimeToItalian($row['Data_Acq']);
            if ($row['dataArr'])
                $Data_Arr = MySqlDateToItalian($row['dataArr']);
            else
                $Data_Arr = "N/D";
            if ($row['dataPart'])
                $Data_Part = MySqlDateToItalian($row['dataPart']);
            else
                $Data_Part = "N/D";
            $NomeCliente = $row['Nome'] . " " . $row['Cognome'];
            $Stato = $row['NomeStato'];
            $CatNome = $row['catNome'];
            $Image = "products/images/" . strtolower($CatNome) . "/" . $row['Image'];
            if($currentOID < $CountOrder && !$inOrder)
            {
                echo "<div class='groupBox'>";
                echo "<div class='simpleRoundedBox'><div class='dataAcq'>Ordine effettuato il: " . $Data_Acq . "</div>";

                $orderID = $row['OID'];

                $SQL = "SELECT SUM(acquisti.Costo) AS TotalePagato
                        FROM acquisti JOIN ordini ON acquisti.IDOrdine = ordini.ID
                        WHERE ordini.ID = '$orderID'
                        GROUP BY ordini.ID";

                $resultTotal = $conn->query($SQL) or die($conn->error);
                $rowTotal = $resultTotal->fetch_row();

                $TotaleSpesa = $rowTotal[0];

                echo "<div class='totalePagato'>Totale pagato: " . $TotaleSpesa . " €</div>";
                echo "<div style=\"clear: both;\"></div></div>";
                $inOrder = true;
            }
            ?>
                <div class="roundedBox">
                    <div class="links">
                        <?php
                        echo "<a href='#' onclick='product(" . $IDProd . ")'>" . $NomeProd . "</a><br>";
                        ?>
                    </div>
                    <div class="tableElements image">
                        <?php
                        echo "<img src=\"$Image\" width='120px' height='120px'>";
                        ?>
                    </div>
                    <div class="tableElements infoProduct">
                        <?php
                        echo "Quantità: " . $Quantita . "<br>";
                        echo "Totale: " . $Costo_Tot . " €<br>";
                        //echo "Acquistato in data: " . $Data_Acq . "<br>";
                        ?>
                    </div>
                    <div class="tableElements">
                        <?php
                        echo $NomeCliente . "<br>";
                        echo "Stato dell'ordine: " . $Stato . "<br>";
                        echo "Spedito in data: " . $Data_Part . "<br>";
                        echo "Ricevuto in data: " . $Data_Arr . "<br>";
                        ?>
                    </div>

                    <!-- Questo mi imposterà per bene la round box attorno ai float! -->
                    <div style="clear: both;"></div>
                </div>
        <?php
            $currentOID++;
            if($currentOID >= $CountOrder)
            {
                $index++;
                if($index < count($countAcq))
                    $CountOrder = $countAcq[$index];
                else
                    $CountOrder = null;
                $currentOID = 0;
                $inOrder = false;
                echo "</div><br>";
            }
            else
                echo "<br>";
        }
    }
    else
    {
        echo "Non hai ancora effettuato acquisti. Torna più tardi!";
    }
?>
</div>

<?php
$conn->close();
?>