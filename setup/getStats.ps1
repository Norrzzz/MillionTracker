try {

    $url = "https://api.nomics.com/v1/currencies/ticker?key=<key>&ids=MM4"
    $result = Invoke-RestMethod -Uri $url
    $price = $result.price
    $circsuply = 1000000
    $marketcap = [decimal]$price * $circsuply
    $volume1d = $result."1d".volume
    $volume1dpct = $result."1d".volume_change_pct
    $volume7d = $result."7d".volume
    $volume7dpct = $result."7d".volume_change_pct
    $volume30d = $result."30d".volume
    $volume30dpct = 0
    $price1d = $result."1d".price_change
    $price1dpct = $result."1d".price_change_pct
    $price7d = $result."7d".price_change
    $price7dpct = $result."7d".price_change_pct
    $price30d = $result."30d".price_change
    $price30dpct = $result."30d".price_change_pct
    mysql -e "UPDATE MillionTracker.stats SET price = $price, marketcap = $marketcap, circSuply = $circsuply, volume1d = $volume1d, volume7d = $volume7d, volume30d = $volume30d, volume1dpct = $volume1dpct, volume7dpct = $volume7dpct, volume30dpct = $volume30dpct, price1d = $price1d, price7d = $price7d, price30d = $price30d, price1dpct = $price1dpct, price7dpct = $price7dpct, price30dpct = $price30dpct WHERE id = 1;" -u cronuser --password=<pwd>
    
    } catch {
            "[$(Get-Date -Format "U")] GetStats# $_" | Add-Content -Path "$PSScriptRoot\Errors"
    }
    