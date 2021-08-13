try {
    Import-Module -Name PowerHTML
    $Holders = @()
    1..20 | ForEach-Object {
        $Url = "https://etherscan.io/token/generic-tokenholders2?m=normal&a=0x6B4c7A5e3f0B99FCD83e9c089BDDD6c7FCe5c611&s=1000000000000000000000000&sid=6f1e2daa35de8391e545ead571f7ed65&p=$_"
        $Response = Invoke-RestMethod -Uri $Url
        $HtmlDom = $Response | ConvertFrom-Html
        $Table = $HtmlDom.SelectSingleNode('//tbody')

        foreach ($Row in $Table.SelectNodes('tr')) {
            $Rank = $Row.SelectSingleNode('td[1]').InnerText
            $Address = $Row.SelectSingleNode('td[2]').InnerText
            $Quantity = [Math]::Round($Row.SelectSingleNode('td[3]').InnerText)
            $Percentage = $Row.SelectSingleNode('td[4]').InnerText -replace '%'
            $Value = [Math]::Round($Row.SelectSingleNode('td[5]').InnerText -replace '\$')
            $Holders += [PSCustomObject]@{
                Rank = $Rank
                Address = $Address
                Quantity = $Quantity
                Percentage = $Percentage
                Value = $Value
            }
        }
    }

    $SQLInsert = ($Holders | ForEach-Object {
        "($($_.Rank), '$($_.Address)', $($_.Quantity), $($_.Percentage), $($_.Value))"
    }) -join ", "

    mysql -e "TRUNCATE TABLE MillionTracker.top1000;" -u cronuser --password=<pwd>
    mysql -e "INSERT INTO MillionTracker.top1000 (rank, address, quantity, percentage, value) VALUES $SQLInsert;" -u cronuser --password=$env:CRONUSER_PWD
} catch {
    "[$(Get-Date -Format "U")] GetTop100# $_" | Add-Content -Path "$PSScriptRoot\Errors"
}
