<div class="cardsection">
    <div class="card" v-if="stats != null" v-cloak>
        <h2>Price</h6>
        <h6>${{ stats?.price }}</h6>
        <table>
            <tr>
                <td title="Price change last 1 hour in percent">1H</td>
                <td :class="{ 'uptrend': price1h > 0, 'downtrend': price1h < 0 }"><span v-if="price1h > 0">+</span>{{ price1h }}%</td>
            </tr>
            <tr>
                <td title="Price change last 24 hours in percent">24H</td>
                <td :class="{ 'uptrend': stats?.price1dpct > 0, 'downtrend': stats?.price1dpct < 0 }"><span v-if="stats?.price1dpct > 0">+</span>{{ price1d }}%</td>
            </tr>
            <tr>
                <td title="Price change last 7 days in percent">Week</td>
                <td :class="{ 'uptrend': stats?.price7dpct > 0, 'downtrend': stats?.price7dpct < 0 }"><span v-if="stats?.price7dpct > 0">+</span>{{ price7d }}%</td>
            </tr>
            <tr>
                <td title="Price change last 30 days in percent">Month</td>
                <td :class="{ 'uptrend': stats?.price30dpct > 0, 'downtrend': stats?.price30dpct < 0 }"><span v-if="stats?.price30dpct > 0">+</span>{{ price30d }}%</td>
            </tr>
        </table>
    </div>

    <div class="card" v-if="stats != null" v-cloak>
        <h2 title="How many people are currently holding Million Token?">Holders</h6>
        <h6>{{ stats?.holders.toLocaleString("en-US") }}</h6>
        <table>
            <tr>
                <td title="Holder change last 1 hour in percent">1H</td>
                <td :class="{ 'uptrend': holders1h > 0, 'downtrend': holders1h < 0 }"><span v-if="holders1h > 0">+</span>{{ holders1h }}%</td>
            </tr>
            <tr>
                <td title="Holder change last 24 hours in percent">24H</td>
                <td :class="{ 'uptrend': holders1d > 0, 'downtrend': holders1d < 0 }"><span v-if="holders1d > 0">+</span>{{ holders1d }}%</td>
            </tr>
            <tr>
                <td title="Holder change last 7 days in percent">Week</td>
                <td :class="{ 'uptrend': holders7d > 0, 'downtrend': holders7d < 0 }"><span v-if="holders7d > 0">+</span>{{ holders7d }}%</td>
            </tr>
            <tr>
                <td title="Holder change last 30 days in percent">Month</td>
                <td :class="{ 'uptrend': holders30d > 0, 'downtrend': holders30d < 0 }"><span v-if="holders30d > 0">+</span>{{ holders30d }}%</td>
            </tr>
        </table>
    </div>

    <div class="card" v-if="stats != null" v-cloak>
        <h2>Volume</h6>
        <table>
            <tr>
                <td title="Total volume traded last 24 hours">Day</td>
                <td>${{ volume1d }}M</td>
                <td><span class="smallpercent" :class="{ 'uptrend': stats?.volume1dpct > 0, 'downtrend': stats?.volume1dpct < 0 }"><span v-if="stats?.volume1dpct > 0">+</span>{{ showTwoDecimals((stats?.volume1dpct * 100)) }}%</span></td>
            </tr>
            <tr>
                <td title="Total volume traded last 7 days">Week</td>
                <td>${{ volume7d }}M</td>
                <td><span class="smallpercent" :class="{ 'uptrend': stats?.volume7dpct > 0, 'downtrend': stats?.volume7dpct < 0 }"><span v-if="stats?.volume7dpct > 0">+</span>{{ showTwoDecimals((stats?.volume7dpct * 100)) }}%</span></td>
            </tr>
            <tr>
                <td title="Total volume traded last 30 days">Month</td>
                <td>${{ volume30d }}M</td>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="card" v-if="stats != null" v-cloak>
        <h2>Tokenomics</h2>
        <table>
            <tr>
                <td title="How much the whole Million market is currently valued at.">Marketcap</td>
                <td>${{ mcap }}M</td>
            </tr>
            <tr>
                <td title="How many coins are in circulation or holding?">Circulating supply</td>
                <td>1,000,000 MM</td>
            </tr>
            <tr>
                <td title="How many Million coins can there ever be?">Max supply</td>
                <td>1,000,000 MM</td>
            </tr>
            <tr>
                <td title="Million token will never be valued at less than $1.">Minimum price</td>
                <td>$1 per MM</td>
            </tr>
        </table>
    </div>

    <div class="card" v-if="ranks != null" v-cloak>
        <h2>Ranks</h6>
        <table>
            <tr>
                <td title="Rank by marketcap on CoinGecko">CoinGecko</td>
                <td><a href="https://www.coingecko.com/en/coins/million" target="_blank">#{{ ranks?.coingecko.toLocaleString("en-US") }}</a></td>
            </tr>
            <tr>
                <td title="Rank by marketcap on Nomics">Nomics</td>
                <td><a href="https://nomics.com/assets/mm4-million" target="_blank">#{{ ranks?.nomics.toLocaleString("en-US") }}</a></td>
            </tr>
            <tr>
                <td title="Rank on DEXTools Hot Pairs list">DEXTools</td>
                <td>
                    <a href="https://www.dextools.io/app/uniswap/pair-explorer/0x84383fb05f610222430f69727aa638f8fdbf5cc1" target="_blank">
                        <span v-if="ranks?.dextools > 0">#{{ ranks?.dextools }}</span>
                        <span v-else>#NA</span>
                    </a>
                </td>
            </tr>
            <tr>
                <td title="Rank by marketcap on CoinMarketCap">CoinMarketCap</td>
                <td><a href="https://coinmarketcap.com/currencies/million/" target="_blank">#{{ ranks?.coinmarketcap.toLocaleString("en-US") }}</a></td>
            </tr>
        </table>
    </div>
</div>

<h2 class="graphheader">Price history</h2>
<div class="graphbuttons">
    <button :class="{ 'active': dataForXDays == 9999 }" @click="dataForXDays = 9999; init()">ALL</button>
    <button :class="{ 'active': dataForXDays == 30 }" @click="dataForXDays = 30; init()">1M</button>
    <button :class="{ 'active': dataForXDays == 7 }" @click="dataForXDays = 7; init()">7D</button>
    <button :class="{ 'active': dataForXDays == 3 }" @click="dataForXDays = 3; init()">3D</button>
    <button :class="{ 'active': dataForXDays == 1 }" @click="dataForXDays = 1; init()">1D</button>
</div>
<div class="graphsection">
    <canvas id="pricegraph"></canvas>
</div>

<br />

<h2 class="graphheader">Holders history</h2>
<div class="graphbuttons">
    <button :class="{ 'active': dataForXDays == 9999 }" @click="dataForXDays = 9999; init()">ALL</button>
    <button :class="{ 'active': dataForXDays == 30 }" @click="dataForXDays = 30; init()">1M</button>
    <button :class="{ 'active': dataForXDays == 7 }" @click="dataForXDays = 7; init()">7D</button>
    <button :class="{ 'active': dataForXDays == 3 }" @click="dataForXDays = 3; init()">3D</button>
    <button :class="{ 'active': dataForXDays == 1 }" @click="dataForXDays = 1; init()">1D</button>
</div>
<div class="graphsection">
    <canvas id="holdergraph"></canvas>
</div>

<div class="about">
    <div class="about_div">
        <img src="/assets/img/golden_lion.png" title="Million Token" alt="Million Token" />
        <div id="about_div_div">
            <p><strong>Million Token</strong> is a decentralized digital currency pegged to a minimum value of 1.00 $USDC with a fixed-supply of 1,000,000 tokens, for a total market cap of 1,000,000+ $USDC. Million Token was founded by an ex-Google / ex-Facebook Tech Lead with over 1,000,000 subscribers on YouTube (as a millionaire).</p>
            <p><strong>Million Tracker</strong> is a tool to watch the progress and real-time metrics of this amazing crypto token without being too daunting for inexperienced users. Info boxes are updates every 2 minutes, while graph is updated every 10 minutes. This is a passion project of mine and any feedback would be much appreciated.</p>
        </div>
    </div>

    <div id="linkToOfficialWeb">
        <a href="https://www.milliontoken.org/" title="Million Token Official Website" target="_blank">Visit official website</a>
    </div>
</div>

<div class="topholders">
    <h2>Top Holders</h2>

    <p>Top 10 holders control {{ top10pct }}% of Million Token. Top 100 holders control {{ top100pct }}% of Million Token.</p>
    <p>{{ top100Unipct }}% are locked into Uniswap pools, and {{ top100Bscpct }}% is allocated to BSC bridge.</p>
    <p>Excluding Uniswap and Bsc bridge, the top 100 wallets control {{ top100OrdinaryWalletsPct }}% of Million Token.</p>

    <br /><br />

    <h2>Search in the top 1000 addresses</h2>
    <div class="search">
        <input type="text" name="search" placeholder="Search by address.." autocomplete="Off" v-model="search" @input="performSearch()" />
        <button @click="performSearch()">Lookup</button>
    </div>

    <table id="searchResult">
        <thead>
            <th>Rank</th>
            <th>Address / Name</th>
            <th>Quantity</th>
            <th>Percentage</th>
            <th>Value</th>
        </thead>
        <tbody>
            <tr v-if="searchResult !== undefined" v-for="holder in searchResult" :key="holder.id">
                <td>{{ holder.rank }}</td>
                <td v-if="holder.address.indexOf('TechLead') == 0"><a href="https://etherscan.io/address/0x5922b0bbae5182f2b70609f5dfd08f7da561f5a4" target="_blank">{{ holder.address }}</a></td>
                <td v-else-if="holder.address.indexOf('Uniswap V3: MM-USDC 2') == 0"><a href="https://etherscan.io/address/0x84383fb05f610222430f69727aa638f8fdbf5cc1" target="_blank">{{ holder.address }}</a></td>
                <td v-else-if="holder.address.indexOf('Uniswap V3: MM') == 0"><a href="https://etherscan.io/address/0x9ac681f68a589cc3763bad9ce43be3380696b136" target="_blank">{{ holder.address }}</a></td>
                <td v-else-if="holder.address.indexOf('Uniswap V3: WBTC-MM') == 0"><a href="https://etherscan.io/address/0xc8d2aaba076bc96505f6442d37deaa583295d030" target="_blank">{{ holder.address }}</a></td>
                <td v-else-if="holder.address.indexOf('Uniswap V2: MM 4') == 0"><a href="https://etherscan.io/address/0xaa934346e4f74bc23e62153ee964df8b826694ef" target="_blank">{{ holder.address }}</a></td>
                <td v-else-if="holder.address.indexOf('AnySwap: BSC Bridge') == 0"><a href="https://etherscan.io/address/0x533e3c0e6b48010873b947bddc4721b1bdff9648" target="_blank">{{ holder.address }}</a></td>
                <td v-else-if="holder.address.indexOf('mm1000orbust.eth') == 0"><a href="https://etherscan.io/address/0xd0bffbccb187be4a775752d27529c5858823dd59" target="_blank">{{ holder.address }}</a></td>
                <td v-else-if="holder.address.indexOf('jacuzzibar.eth') == 0"><a href="https://etherscan.io/address/0x8fba8f8955c03dac1de908d310a3eb835dabcc91" target="_blank">{{ holder.address }}</a></td>
                <td v-else-if="holder.address.indexOf('bitcoinwizard.eth') == 0"><a href="https://etherscan.io/address/0x913738e96dcd3bad50a4a286fc9a6941e156cdd4" target="_blank">{{ holder.address }}</a></td>
                <td v-else-if="holder.address.indexOf('9xkiwi.eth') == 0"><a href="https://etherscan.io/address/0x4d3877abc39d131a39ce21dd0675ad3c1e3f3f32" target="_blank">{{ holder.address }}</a></td>
                <td v-else-if="holder.address.indexOf('Gate.io') == 0"><a href="https://etherscan.io/address/0x0d0707963952f2fba59dd06f2b425ace40b492fe" target="_blank">{{ holder.address }}</a></td>
                <td v-else-if="holder.address.indexOf('buymilliontokenatmilliontokendotorg.eth') == 0"><a href="https://etherscan.io/address/0x7c7b99634cd523b4af7722f612f68b063db07e36" target="_blank">{{ holder.address }}</a></td>
                <td v-else-if="holder.address.indexOf('0x') == 0"><a :href="'https://etherscan.io/address/' + holder.address" target="_blank">{{ holder.address }}</a></td>
                <td v-else>{{ holder.address }}</td>
                <td>{{ holder.quantity.toLocaleString("en-US") }}</td>
                <td>{{ holder.percentage }}%</td>
                <td>${{ holder.value.toLocaleString("en-US") }}</td>
            </tr>
        </tbody>
    </table>
    <p v-if="searchError != null">{{ searchError }}</p>
</div>