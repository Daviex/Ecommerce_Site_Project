<link rel="stylesheet" href="asset/styles/manageOrders.css" type="text/css">

<?php
include("../include/BasicLib.php");
?>

<script type="text/javascript">
    function manageOrder(IDAcq) {
        $.post(
            "manageOrders.php",
            {
                IDAcq: IDAcq
            },
            function (msg) {
                loadPageWithFXAjax(msg);
            }
        );
    }

    $(document).ready(function(){
        $("#userID").change(function() {
            var IDUser = $("#userID").val();
            $.post(
                "manageOrders.php",
                {
                    IDUser: IDUser
                },
                function (msg) {
                    loadPageWithFXAjax(msg);
                }
            );
        });
    });

    $(document).ready(function() {
       $("#submit").click(function() {
           var IDAcq = $("#purchaseID").val();
           var IDState = $("#IDStato").val();
           var DataPart = $("#dataPart").val();
           var DataArr = $("#dataArr").val();

           $.post(
               "manageOrders.php",
               {
                   IDAcq: IDAcq,
                   IDState: IDState,
                   DataPart: DataPart,
                   DataArr: DataArr
               },
               function(msg) {
                   loadPageWithFXAjax(msg);
               }
           )
       });
    });
</script>

<div id="manageordersPage">
    <div class="title">
    Modifica ordine
    </div>
    <div class="infoOrder">
        <?php
        if(!isset($_POST['IDAcq']) && !isset($_POST['IDUser'])) {
            $SQL = "SELECT *
                    FROM utenti
                    ORDER BY ID";
                    $result = $conn->query($SQL);

            if ($result->num_rows > 0) {
                echo "Utente: <select id='userID'>";
                echo "<option>Scegli un utente</option>";
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['ID']."'>".$row['Username']."</option>";
                }
                echo "</select>";
            }
            ?>
        <?php
        }
        elseif(!isset($_POST['IDAcq']) && isset($_POST['IDUser'])) {
            $IDUser = $_POST['IDUser'];
            $SQL = "SELECT  acq.ID AS IDAcq, prod.Nome AS prodNome, ord.Data_Acq AS dataAcq, state.Nome as stateNome
                    FROM (( acquisti AS acq JOIN prodotti AS prod ON acq.IDPRod = prod.ID ) LEFT JOIN stati AS state ON acq.IDStato = state.ID ) JOIN ordini AS ord ON acq.IDOrdine = ord.ID
                    WHERE ord.IDUser = '$IDUser'
                    ORDER BY dataAcq DESC";

            $result = $conn->query($SQL);

            $table = "";

            $table .= "<table>
                    <tr>
                    <th>Prodotto</th>
                    <th>Data Acquisto</th>
                    <th>Stato</th>
                </tr>";

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $table .= "<tr onclick=\"manageOrder(" . $row['IDAcq'] . ")\">";
                    $table .= "<td>" . $row['prodNome'] . "</td>";
                    $table .= "<td>" . $row['dataAcq'] . "</td>";
                    $table .= "<td>" . $row['stateNome'] . "</td>";
                    $table .= "</tr>";
                }
            }

            $table .= "</table>";

            echo $table;
        }
        elseif(isset($_POST['IDAcq']) && !isset($_POST['IDUser'])  && !isset($_POST['IDState'])) {
        ?>
            <?php
            $IDAcq = $_POST['IDAcq'];
            $SQL = "SELECT prod.Nome, state.Nome, state.ID AS stateID, Data_Part AS dataPart, Data_Arr AS dataArr
                    FROM ((prodotti AS prod RIGHT JOIN acquisti AS acq ON prod.ID = acq.IDProd)
                          LEFT JOIN stati AS state ON acq.IDStato = state.ID)
                          LEFT JOIN spedizioni AS sped ON sped.ID = acq.IDSped
                    WHERE acq.ID = '$IDAcq'";

            $result = $conn->query($SQL);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $IDStato = $row['stateID'];

                    echo "Stato: <select id=\"IDStato\">";
                    $resultStati = $conn->query("SELECT * FROM stati");
                    while ($rowStato = $resultStati->fetch_assoc()) {
                        if($IDStato != $rowStato['ID'])
                            echo "<option value='".$rowStato['ID']."'>".$rowStato['Nome']."</option>";
                        else
                            echo "<option selected value='".$rowStato['ID']."'>".$rowStato['Nome']."</option>";
                    }
                    echo "</select>";
                ?>

                <br><br>

            <?php

                if(empty($row['dataPart']))
                    $dataPart = "00/00/0000";
                else
                    $dataPart = MySqlDateTimeToDate($row['dataPart']);

                if(empty($row['dataArr']))
                    $dataArr = "00/00/0000";
                else
                    $dataArr = MySqlDateTimeToDate($row['dataArr']);
            ?>

                Data Partenza:&nbsp;<input type="date" id="dataPart" name="DataPart" value="<?php echo $dataPart; ?>" min="1" max="100"><br><br>
                Data Consegna:&nbsp;<input type="date" id="dataArr" name="DataArr" value="<?php echo $dataArr; ?>" min="1" max="100"><br><br>

                <input type="hidden" id="purchaseID" value="<?php echo $IDAcq; ?>" >

                <button id="submit">Conferma</button>
            <?php
            }
            ?>
        <?php
        }
        else {
            $IDAcq = $_POST['IDAcq'];
            $IDSped = $conn->query("SELECT IDSped FROM acquisti WHERE ID = '$IDAcq'")->fetch_assoc()['IDSped'];
            $oldDataPart = $conn->query("SELECT Data_Part FROM spedizioni WHERE ID = '$IDSped'")->fetch_assoc()['Data_Part'];
            $oldDataArr = $conn->query("SELECT Data_Arr FROM spedizioni WHERE ID = '$IDSped'")->fetch_assoc()['Data_Arr'];
            $DataPart = $_POST['DataPart'];
            $DataArr = $_POST['DataArr'];

            $IDState = $_POST['IDState'];
            $oldIDStato = $conn->query("SELECT IDStato FROM acquisti WHERE ID = '$IDAcq'")->fetch_assoc()['IDStato'];

            $SQL = "";

            if(!empty($DataPart) && $DataPart != $oldDataPart)
            {
                $SQL .= "UPDATE spedizioni SET Data_Part = '$DataPart' WHERE ID = '$IDSped'; ";
            }

            if(!empty($DataArr) && $DataArr != $oldDataArr)
            {
                $SQL .= "UPDATE spedizioni SET Data_Arr = '$DataArr' WHERE ID = '$IDSped'; ";
            }

            if($IDState != $oldIDStato)
            {
                $SQL .= "UPDATE acquisti SET IDStato = '$IDState' WHERE ID = '$IDAcq'; ";
            }

            if ($conn->multi_query($SQL))
            {
                echo "Ordine aggiornato con successo!";
                ?>
                <script type="text/javascript">
                    setTimeout(function () {
                        homepage();
                    }, 1500);
                </script>
            <?php
            }
            else
                echo "Errore 1: " . $conn->error;
        }
        ?>
    </div>
</div>
<?php
$conn->close();
?>