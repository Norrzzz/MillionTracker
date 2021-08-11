try {

    $Url = "https://api.coingecko.com/api/v3/coins/million"
    $Headers = @{
        "Accept" = "application/json"
    }
    
    $Response = Invoke-RestMethod -Headers $Headers -Uri $Url
    $CoinGeckoRank = $Response.market_cap_rank
    
    $Url = "https://api.nomics.com/v1/currencies/ticker?key=<key>&ids=MM4"
    $Response = Invoke-RestMethod -Uri $Url
    $NomicsRank = $Response[0].rank
    
    $Url = "https://pro-api.coinmarketcap.com/v1/cryptocurrency/map"
    $Headers = @{ "Accept" = "application/json"; "X-CMC_PRO_API_KEY" = "<key>" }
    $CMCRank = (Invoke-RestMethod -Uri $Url -Headers $Headers -Body @{ "symbol" = "MM" }).data | Where-Object name -eq Million | Select-Object -ExpandProperty rank
    
    $Url = "https://www.dextools.io/chain-ethereum/api/dashboard/uniswap/hot"
    $HotPairs = Invoke-RestMethod -Uri $Url
    $DEXToolsRank = 0
    $rank = 0
    $HotPairs | ForEach-Object {
        $rank++
        if ($_.id -eq '0x84383fb05f610222430f69727aa638f8fdbf5cc1') {
            $DEXToolsRank = $rank
        }
    }

    mysql -e "UPDATE MillionTracker.ranks SET coinmarketcap = $CMCRank, nomics = $NomicsRank, coingecko = $CoinGeckoRank, dextools = $DEXToolsRank WHERE id = 1;" -u cronuser --password=<pwd>
    
} catch {
        "[$(Get-Date -Format "U")] GetRanks# $_" | Add-Content -Path "$PSScriptRoot\Errors"
}
    