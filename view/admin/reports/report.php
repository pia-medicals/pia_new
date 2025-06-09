<div class="dashboard_body content-wrapper">


  <section class="content">
      <div class="row">
        <div class="col-xs-12">
  <?php $this->alert(); ?>

          <div class="box">
            <div class="box-header">
              <h2 class="box-title">Reports</h2>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="reports">
                 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                   <div class="reports-log"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQEAAADECAMAAACoYGR8AAAA2FBMVEX///++IC67ABi8EyXpv8LQcXe8Ch+7ABrdl5y9GSjmtbjHUFi+HSy6AA8AAFIAAE7z4OG5AAr47u6/JTLYi5AAAFC6ABS5AAAAAEvx2tvOztmGhaDGQ02vr76WlKvenqLAv8sAAEPc3ORpZ4v29vgpJmITDlruzc+jorWZmK/p6e1MSnc2M2ry8vXDN0IAAEfOZ27GxdJ/fps8Om3DOUTW1t9bWIPhqKzsyMtXVX7RcXgAAEFFQ3IbF1vVgofKV18rKGNwb44jH2BqaYvNYGjJVFwAACwAADW02uKlAAARvElEQVR4nO2dCXeqOtfHKVgQC6goOKJWqfNcrbWn2mp7nvf7f6M3A2jAKVC0cq7/te5dWiXDL8nOzk7iYZibbrrpppsuJFVNNCKx2WxWjkbT6XS0XJ7NYrHXXEI1f7toZ5aaiJSX38UUx8o6KwgCv5UgsKws81L/4S0ay6m/XdIzSH0tD/uczvK8KEl3ByVJIscLuiA9pGO5f6ZDmI3yUGJ1njtWdTcJEXKYLGOJ3y79j9WIFpOw8tR1d3DgBZkdznK/XQnfSsy+Qe1FX5XfiuNl/j4SQsuQi04ywm7t0UDngdljdV0GAhYR2EHuqHG4kzg2+TELFYTcMgXsurMWIs+DWov94jBdnsUir41cLpEAsyKYE9PDh0mKlwEM8SAIUUj+DQuERLTvqL7Ewar3P5YzMM0drIMJpspZ+nsCQBziIArC2+sla+JL6uyBqD5oeFaeDMuxBnXrqYlY+juVZPfbD05PRa96emjc86xdcokTZP4j7c+GJWLLYpLdN4VIvP4RCbrcAUmd9XW7zJyQTN3/cBpTY/d3yX1dQdT7sYDKHKRy98CTs1tJHwbkyuTKD3tnFFYsB5J+cIo8JDncPnwyFW0EmTSwLBl+ZzhIAn9NDGJ9WbRavzg7g6FSy9vxRRqEa2EQS+kSLtGkfDY7nVvywk5HEFLXYBNjKVZC09Rd+rz+u1pO6W6LIMkPv71oiPRh+4Pm/76EqxIpypx7XpDTF8j4oHIPMqw/K6Uv5aU0vncY8L83FNQ3aP9EuT+7ZCgj9510jQVJfvudWEpZAK0hJh8u7qk3UM9zdAPxF5YLrylgmkH9A536aRVJCe5ucH/hIqjD5O/VH6qcdJkDvn/RBdNM5+4kffKbK1X1PukcCmJydrnM/8rAKf11Z6TRdw2F5NuFco7w3B0vXINDmpadswI/uUgMCfQ+UX67jnBVztUNRP78AzPR5+/Yya8ZwB2lndZAypzbGMQEkWOvYQBs1LjjncYgetbslrKkP1xZmM4c6g4E+hk9A/VBEOXLTTnUijpHAjs8V0Y5kRMuY2y96tW5aha+z5RNUkwuz5P0j6WmHB4i/3COTGIZ7gJTjW8V+XMjKGf44lWOAFtD1oHgI+j0o0n5Ui6nXy0dUwIfsC1Iy8mrcgL2Ku1AwAY6KS5l+beXQTSKyg6/IEDXKJ3kfjsgS6e0A4Ec2M5aNCNdmRt4UI6BIMkBLV/KmdRVTwIOLckZQeICKXgsMwnTobZv0i/gigGk2AgXAIaZkN4h//MpPBE2AIzpOIHz49ihKfZDBgAs4JwTwg+N+F8+PEZwoxmJQJz8KK1lJizToENvpDUUfrKxGsmEwxHaUZ8MF/ygEmrmGo8r0ShHOkai/ymx+Ksb8z9SmXSMfEd2o2cJtFxIRWIcSKw/c57QQzcPEkqQ8wHnzy/qX8+uiB+lyfnA1xIpet6Nh/PrjnAN/RhDNYg1xa8qQo4D3btz/B1CX9ClB9IYprw+/Xr9UcGTymVIz9Brhc6273RJvRHrZIn3NrP9A1fcgBJJohPw3iz7PzAGoO4dncDLk7Ew+0KEHJ1A8DIdhGFvgErfZCfwMB2EdEm8Rw3SJ5Dp933/HQKOBRIXgglOVRux2TLIoMSMXB0kab28cppG0fIsqF8JQBVPDx/6nKyzvBzAguShb4uMG1PfS3ogfxfhmFiZTf0t+x4zaiIHK17sczr8VQLOinJ7nLj3qi9KlggA9LawKIPS3NFJEnl54i8k/8HbFXceDw6CwCQj77vyrlO2VuK1vCxm3Pemj1BgOT+FLh7IIAgCjJlrlD8yO5dRPIX9cmme31dAd9dCfxNS3kMQ0e++LO/5aYZACCCpQ/clBK8rRMc+LC6dLnBAvKC7ii5lfEVV1Ub5232HLjgCYIHn6gW0w2CjN2cCkpC2rL+aaMyGouMQH+v76NLMiSBIAqqrE3hOO+HsBLyrq0eKOlF2/6e3csKPSnlMRac5FD2Hv53T6W7RYuSkIfg+uuSIagZK4Ns1IXjeBnWMI31P0CCRIhgl/XpzDXKL55x94I71uugjG2f/kZQcMYi9xmE2UsnRFqgdcE9ngtdGKpME9k8lM6L0vN9jx2RPCpLAq/PcPVgdeT1nSUHAUXrd50KhfyYC6R2XxusG2oyw0ocIkN/x5nRtVTwTgcnudX2PLjwNAVU//Z1TOhOBnBUlI3ePPM6HNAQcoGV/K0VycyNAAvYgIAEnvYXBYzQElsSUyftbJ56JAF7BcPdkNTwmT0WA7Cg+I1HnIWDNBPqrSQ7Uvqc0qAiQU47k7+TWeQjgZQ3cKCA3DpKeBioVAfLQjiT5corOQsDEgWLopJAxY29OCxUBx3ENfx7BWQjE2G2bEw6HxHlKhIaA6gjK+9pxPAsBnCgel1HCNdK93BfzQcDXdHgOAirePMcBYtJp8WStw0zA8uitTQJy/yzjwVZ5twPXMwrwesVeCkWITuBlgUhFwLG6l6/FElonzHU7IEAs4Lx4xlQEyC95D8IgnYHAEg0CSbTfk8tE6u0zSgKOMIo3j8vWGQjgHajt5J8geqqHn/WjIkAW33socieJYAhY454wzEQeHtqJhoDpGAT+AgTBE8ARTtJJjxHBLPp9AxoCEccxbn/HTwInYAUIye5uEhFN+oaiIUCW/k7wFyYLnIAVvXT46MQqnj6SQ0HAca9J/OuvwIETwAmKjk0csqQ67T4nBYEJ2QV0n2ewgiZgGX7XmCSWRxzt7s5pAkvSDvoNEwZOIGo5A073lwjluD86qJMEysFc9g6aAG5tzhUJUImDxrQm+xSBpQMA5/taTsAErIDIzmAnNgFpt06OE4j0HRsSks+JgAmcALb6u37PK9FglIU9QiBRnujOc0o+I+VQAROwPOLdhIjlEeXWiZNAIpHL5RK511g5OuzrrmNAIvUEs0fBErA94t2FOhEqovTfHcs+Cf2jGzI+AebejhJSPzmQHywBPNz3VVEljlrTbZ04CBwR7+8Y0UaBErBiVuy+ielD9JgPDQGJY/XlDw+XBkrAmvaT+z4jQkV0C8QTBCSJ43V++PPrCIESwOdGDtyyJI4CUm2dOAmQ/wgZy+qyIPWH/g/VkgqSQAL7PQcuWRLhHKqtE2cALF22NZvFIo0A/x26IAlYHvHd/k+J5RHVvRsHgZ/+nsURBUlAOuQM7GRFs44LIQHLIz441xGhIpqtkxASWB50BrBUwpWh2DoJIQEcCzuyKUKEiii2TsJHwIpa8rHIIXnzjMNHwD5Gy7OH5O3mUegIqDsXAo7q9NZJ6AjMKBcylk57xqEj4D5JfUont07CRsA60sQlj4qwBCe3TsJGAHv93L2pHhNxDfNkdDtsBHAU7NRBIZOIk5yKbIWMAPaIDy2KtiKO1JzaOgkZAXxw8vSql9jslbjjnnG4CJjY5afYGiei5ie2TsJFAC/7aLbu7qm3TsJFAIdBaZ4nT9kev3USKgJWjJiqmBLt1kmoCOAjlHQ/QZamXSCGigC+60J3xyNHu3USJgJWpTJ0WxfEaYqjGYaJAO7YtMf5iDjJ0QVimAhgZ2DvXtkekYehj216h4iAddcnSbuHQWR5zIkMEQF8qYj+7gB5qOjI1kmICOC9MvrDbGQ87chT4SFgecQe7hARG+lHek54COAU3KfHjok8Z3x46yQ0BGyP2MNJHnLz6PDWSWgIYI/Y24lO4ndmDnsRoSGAXTxvZ/vJu7IHLweFhYAVI/Z2oZYh/rmPg1snYSFgXSry+CvTSwrPOCwE8G8seLg+hERekjsUWotliLNDZ/wXPR44cSPWO4FIBp109FxALinbyhyYR3Pp6Fbp8/2Q97C41cT7TwXDs65Qfp/z9fBNN91000033XTTTTf9RzRtGlBT8hXTxi8No+L4qtH8Qi9GRiXfZtoVYzxF76vgizAK12u32z383bb1TLvSBF+qgg/sMJ31avtVmLAxgm8qdrbVTZ5me/t9K81ej3weZLBCX6+A5/AX8nkrzSpjjowC+msbFHG6ydAhc/qsaGtl8N4zp++K8lJb1yuMWX1R4q2K0eyiFFfdTqfVY4yVosC01/XafP0/Jl9aZGFe7Y7SNFbxFvikoGgKyqaqWMmDL8VHTHMez35ZtX0coCI1F9m5heT9yTDeNfB8r1JXaqAmXWNTvF5JyxbgC6OWza7Qn8Yl9Pw6/o5ao1DPIgLtppZFDWYqoObzbK32/H/5dvNZg18vDTpfnUFhLwHwqQYyrGigPC2lBt7XYIoVRYHom/C/bK3HTOsL0ASfdfD+6QlWcgTLEocEugpsnNYAVIEpLOpjROBpk/wYEGBM7bmOW6DQreP61RYKbtTPNcyp+wcWv6PAD6tNonif6wV+sV4rqMsVWujt10LDXaUWt7pMS1ujDEEJxllYvirIoBIHBEao8ub0EIE4zHuebQMCsPjVLChA7xk1JkiiqqHaGOsx067X4cc1+8mOAggYcVRnZqGBipQK3fjUSaAACTDZVhxX/Kml4ReLTh1Vxcii+lZHVbs1tmMI/r3bVUb4gZbyThB46tRLGIVNADRQAZYPFPwzvqmdUoJlyB+oPEEAksQE2lkD1a5nJ7HpPFWlDj9+tt93IYGVgmv0ooBkSq2K9r6PwOPoHXX6/LyCUVRe8gpKaBzfjnqmGd8OACv7VlPBA0jp4R6CCYzmeQU1+bYPgI4bbzPP8OOuZkMcQwIlrckcESbQAU2ICVQGbUQANGkVvJ/DmhEEwCc1kyDwbDVRQQFZl0ogIWMfgXwrC5MdVwxcyVqF+dRgd5krRIs3cVmI0i2qPcu2KO12fNGzCXwZOHuSADNfzCvIArWUrvXHAiRQ1RQ3WScBUMTmYwk+twLWqIae7SwKhcK6BGu46UGYQPtJWRhbAk/W56ivAAJTaEH29IF2FpZcMTEBM2uCJyBwZCCqhVIJfq2p1AqF+aaTAcPZhb3LwARAg44tAiagAovrJFBV1lYnqmlawdwQYJqa1iG6mpsAyLX2NULkxqtHa5R36vlqflVibNJbAow5VrTnqU1g7SKAirmHAPPchYOAwQQMkE1bWdgEmPZXFpe0VZ3iGch6ugU7d8ciYC6AMUQEKnP4vOIiwIwX9rPGQkMmFxNg8u+aZa/2EYiPrFct0AefNfwO2QETPP2u2B/bBMCLmgKzRQRsQ4VMDiRgKkq1vYdAazBlVoZFoGOYPbMDB5g1CkZxRAB9SBR1MTVNs65UMQHG0DqYQA08Dx41XASaysZMmwUl3twQgKZcmR8kUCEIjLTnLQGol+0Q2hCADf1lEfiyPi8oTUwAFGPe/twlUI0XGND3EYFevPby8lJb1KAlnLoJbJVfgK+9zFEtEKl3rVJqQcro+fr8MAHYd9YEAab3qR2YEZwEwAgyCAJT0LZfewi04cyECDQ1PFfMYfqIANOtF7YdeUOAeV+Pagwm0MTOzRzMXBU8GzoIbKq0wjYc9W1EIK90xy08iIBeoMm3CFRNNwGAyyQIgIoeMIcuAlVlYTLWXACKAKttG2uCQBWOKkSgF/+Ef+gpsONjAnltsTVmWwLN+qJiEXjGrWHAiffpc4fA2PZfrXmiAJ1P/Hq1qAMqHVxkRM8iMG7vEOh2yT4AUjngEpUGNoHSHzQKB8hEZwvNZrMG2qqV7UAY7REzHWigV6xAOuYcTpnrAaxI5RF8qTdHrws4u5qyJTD+AwnA/7U16KcYwCtuZ/FnPQ2UcapAM10ZIAKDjtFslhRruq1Y8+J0ALKIIwLA/LWYnvU8k4VTRfYLlLQAHSFA4AUXAK5TSo8GrA30iJrg2Xz2ZT8A4O7baKoVmIk5gkTyowoU/KiyHszH7+9TMFOCelQfH7OLxznkPqrgRcBq/bReofJZi5P2auuC40S/YELIho1WI7AEspnDx3qlbr07L8GvVUcoW9v2Nu0X0NPFOYDiAmNlP98Cf5xaz5gEge6fQT2+hvWYVkBJC38GipLddAYfqk4Jr4VpV6tX/48j9pxFNqvTw+7ATTfddNN/XomDV9j/Qe09flHOHLzD/s8ps/dmRlnm/jNK7iUQGd7/ZzT08pv+N91000033XTTTf+y/h+CdbzkOfh7ywAAAABJRU5ErkJggg=="></div>
                 </div>
                 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p15 ">
                   <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                     <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Patient Name :</th>
                            <td><?php echo $patient['DisplayName']; ?></td>
                          </tr>
                          <tr>
                            <th>Patient ID : </th>
                            <td><?php echo $patient['ID']; ?></td>
                          </tr>
                          <tr>
                            <th>Patient DOB :</th>
                            <td>
                              <?php foreach ($patient['DOB'] as $key => $dob) { 
                                        print_r($dob['Name']);   ?>
                              <?php  } ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Accession ID # :</th>
                            <td>NULL</td>
                          </tr>
                          <tr>
                            <th>Study Date:</th>
                            <td><?php print_r($patient['StudyDate'][0]); ?></td>
                          </tr>
                        </thead>
                        
                      </table>
                   </div>
                   <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 logo2"> 
                    <img src="https://cdn0.iconfinder.com/data/icons/medical-5/450/pulse-512.png">
                   </div>

                 </div>

              </div>
            </div>
          </div>
      </div>
    </div>
  </section>


</div>
<script type="text/javascript">


</script>