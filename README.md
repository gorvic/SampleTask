# Test task (homework)

**config.ini** structure: <br>
- **[general]** section <br>
_**BIN_DRIVER**_ - the additional CC information driver ('LookupBinlistNet' with real time requests, 'BinEmulator' contains preloaded data)<br>
_**RATES_DRIVER**_ - currencies rates driver  
('ExchangeratesApiIo' with real time requests, 'RatesEmulator' contains preloaded data)<br>
_**EU_MULTIPLIER**_ - Europian country transactions multiplier <br>
_**NON_EU_MULTIPLIER**_ - Other countries transactions multiplier <br>
- **[LookupBinlistNet]** section <br>
_**URL_PATTERN**_ - url pattern to get information from lookup.binlist.net<br>
- **[ExchangeratesApiIo]** section <br>
  _**URL_PATTERN**_ - url pattern to get information from api.exchangeratesapi.io<br>
  _**API_KEY**_ - personal API code for api.exchangeratesapi.io 