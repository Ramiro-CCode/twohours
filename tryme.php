<!DOCTYPE html>
<html>
<head>
    <title>Pray that it works!</title>
</head>


<body>
    <?php
        // Connect to the database, adjust to you necessities
        $conn = mysqli_connect("localhost","normaluser","1234", "twohours");
        
        
        // Open a file for writing the GEDCOM data
        $gedcomFile = fopen('peacefam.gedcom', 'w');

        if($conn!=NULL){
            print("Succesfully connected. <br><br>");

            // Get the data for each family member, you'll probably want to change the query
            $result = mysqli_query($conn, "SELECT * FROM peacefam");

            // Loop through each person in the database
            $i = 1;
            while ($person = mysqli_fetch_assoc($result)) {
                // Write the individual record for this person, this includes name, age and cause of death
                fwrite($gedcomFile, "0 @I{$person['id']}@ INDI\n
                1 NAME {$person['name']}\n
                1 DEAT\n
                2 AGE {$person['age']}\n
                2 CAUS {$person['death_cause']}\n
                1 NOTE Last words: {$person['last_words']}\n");

                // Write the family connections, it checks if Eve
                if ($person['parent_id'] > 0) {
                    fwrite($gedcomFile, "1 FAMC @F$i@\n
                    0 @F$i@ FAM\n
                    1 WIFE @I{$person['parent_id']}@\n
                    1 CHIL @I{$person['id']}@\n");
                    ++$i;
                }
            }

        // Close the file
        fclose($gedcomFile);

        // Close the database connection
        mysqli_close($conn);

        }
        else{
            print("Error connecting servah.<br>");
        }
        print("<br><br>Done!");
    ?>
</body>

</html>
