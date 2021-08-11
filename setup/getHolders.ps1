
try {

        $Response = Invoke-RestMethod -Uri "https://etherscan.io/token/0x6B4c7A5e3f0B99FCD83e9c089BDDD6c7FCe5c611"
        [int]$EthHolders = (($Response -split "`n" | sls "token holders") -split " <")[0].trim() -replace ","
    
        $Response = Invoke-RestMethod -Uri "https://bscscan.com/token/0xbf05279f9bf1ce69bbfed670813b7e431142afa4"
        [int]$BscHolders = (($Response -split "`n" | sls " addresses") -replace " addresses").trim() -replace ","
        $Holders = $EthHolders + $BscHolders
    
        mysql -e "INSERT INTO MillionTracker.holdersv2 (total, eth, bsc) VALUES ($Holders, $EthHolders, $BscHolders)" -u cronuser --password=<pwd>
        mysql -e "UPDATE MillionTracker.stats SET holders = $Holders WHERE id = 1;" -u cronuser --password=<pwd>
    
    } catch {
           "[$(Get-Date -Format "U")] GetHolders# $_" | Add-Content -Path "$PSScriptRoot\Errors"
    }
    