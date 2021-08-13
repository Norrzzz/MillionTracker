try {
    $nomicsKey = $env:NOMICS_API_KEY
    $Url = "https://api.nomics.com/v1/currencies/ticker?key=$nomicsKey&ids=MM4"
    $Response = Invoke-RestMethod -Uri $Url
    mysql -e "INSERT INTO MillionTracker.price (price) VALUES ($($Response.price))" -u cronuser --password=$env:CRONUSER_PWD
} catch {
    "[$(Get-Date -Format "U")] GetPrice# $_" | Add-Content -Path "$PSScriptRoot\Errors"
}
