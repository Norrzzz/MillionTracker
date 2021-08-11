try {
    $Url = "https://api.nomics.com/v1/currencies/ticker?key=<key>&ids=MM4"
    $Response = Invoke-RestMethod -Uri $Url
    mysql -e "INSERT INTO MillionTracker.price (price) VALUES ($($Response.price))" -u cronuser --password=<pwd>
} catch {
    "[$(Get-Date -Format "U")] GetPrice# $_" | Add-Content -Path "$PSScriptRoot\Errors"
}
