<template>
    <div>
        <div class="d-flex justify-content-between mb-3">
            <h2>Liste des sociétés</h2>
            <a href="#" class="text-white h2 btn btn-success font-weight-bold" data-toggle="modal" data-target="#newClient" >
                <i class="fa fa-plus" aria-hidden="true"></i> Nouvelle société
            </a>
        </div>
        <div class="card-body table-responsive p-0">
            <PageLoader :is-loading = isLoading ></PageLoader>  
            <table class="table table-bordered">
                <thead class="thead-blue">
                     <tr>
                        <th class="p-2 border-right border-white h6">#</th>
                        <th class="p-2 border-right border-white h6">Nom</th>
                        <th class="p-2 border-right border-white h6">Email</th>
                        <th class="p-2 border-right border-white h6">Telephone</th>
                        <th class="p-2 border-right border-white h6">Adresse</th>
                        <th class="p-2 border-right border-white h6">Pays</th>
                        <th class="p-2 border-right border-white h6">Fournisseurs</th>
                        <th class="p-2 border-right border-white h6">Type Commmande</th>
                        <th class="p-2 border-right border-white h6 text-center">Logo</th>
                        <th class="text-right p-2 border-right border-white h6">Action</th>
                    </tr>
                </thead>
             <tbody class="bgStripe">
                    <template v-if="!clients.data || !clients.data.length">    
                         <tr>
                           <td colspan="9" class="text-center">Aucune société trouvée!</td>
                        </tr>
                    </template>
                      <tr v-for="client in clients.data">  
                        <td class="p-2 align-middle">
                            {{ client.id }}
                        </td>
                         <td class="p-2 align-middle">
                            {{ client.nom }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ client.email }}
                        </td>
                        <td class="p-2 align-middle">
                            {{ client.telephone }}
                        </td>
                        <td class="p-2 align-middle">
                           {{ client.adresse }}
                        </td>
                        <td class="p-2 align-middle">
                           {{ client.pays }}
                        </td>
                        <td class="p-2 align-middle">   

                            <span class="badge bg-success mr-2 p-2 mb-2" v-for="value in client.fournisseurs">
                                <template v-for="fournisseur in listFournisseurs">
                                    <template v-if="value == fournisseur.id">
                                        {{fournisseur.fonmfo}}
                                    </template>
                                </template>
                           
                            </span>            
                           
                        </td>
                         <td class="p-2 align-middle">
                          <template v-for="value in client.typeCmd">
                                <template v-for="type in listTypeCmds">
                                    <template v-if="value == type.id">
                                        <span class="badge mr-2 p-2 text-white mb-2"  v-bind:style="{ background: type.tcolor }">
                                        {{type.typcmd}}
                                        </span> 
                                    </template>
                                </template>
                           
                            </template> 
                        </td>
                        <td class="p-2 align-middle text-center">
                          <template v-if="client.logo">
                              <img :src="'/images/logo/'+client.logo" style="height: 40px;">
                          </template>
                          <template v-else>
                             <img src="/images/logo/no-image.png" style="height: 40px;">
                          </template>
                          
                        </td>
                         <td class="p-2 align-middle">
                            <div class="d-flex justify-content-end align-items-center">
                                <a title="Editer" href="#" class="btn m-1 btn-circle border btn-circle-sm m-1" v-on:click="editClient(client)" data-toggle="modal" data-target="#newClient">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                <a title="Supprimer" href="#" class="btn m-1 border-danger btn-circle border btn-circle-sm m-1" v-on:click="deleteClient(client)">
                                    <i class="fa fa-close text-danger" aria-hidden="true"></i>
                                </a>
                            </div>
                             
                        </td>
                       
                    </tr>
                </tbody>
            </table>
        </div>
         <div class="d-flex mt-4 justify-content-center">
            <pagination
                :data="clients"
                @pagination-change-page="getClient"
            ></pagination>
        </div>
         <!-- Modal Clients-->
        <div class="modal fade" id="newClient" tabindex="-1" role="dialog" aria-labelledby="myModalClient"
          aria-hidden="true"  data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                
                    <div class="modal-header text-left">
                        <h4 class="modal-title w-100 font-weight-bold">
                            <template v-if="modeModify">Modification Société</template>
                        <template v-else>Nouveau Société</template>
                        
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" v-on:click="closeModal()" aria-label="Close" ref="closePoup">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                         <form @submit.prevent="saveClient" enctype="multipart/form-data" key=1 >
                            <div class="row">
                                <div class="col-12 d-flex justify-content-center">
                                    <div v-if="this.submitted && $v.clientForm.value.$error" class="text-center">
                                      <span v-if="clientForm.value" class="badge bg-danger mr-2">Choisir minimum 1 fournisseur</span>
                                    </div> 
                                     <div v-if="this.submitted && $v.clientForm.valueTypeCmd.$error" class="text-center">
                                      <span v-if="clientForm.valueTypeCmd" class="badge bg-danger mr-2">Choisir minimum 1 type de commande</span>
                                    </div> 
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    <div class="w-100 d-flex align-items-center my-2">
                                       <label for="nom"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Nom
                                       </label>
                                        <input class="w-65 form-control" id="nom" v-model="clientForm.nom" 
                                        :class="{ 'border-danger': submitted && !$v.clientForm.nom.required }" />
                                    </div>
                                    
                                 </div>
                                  <div class="col-6 my-2 d-flex flex-column justify-content-start align-items-center">
                                    
                                    
                                    <div class="w-100 d-flex align-items-center my-2">
                                         <label for="adresse"  class="d-block m-0 text-right  w-35 pr-2" style='white-space: nowrap;'>
                                        Adresse
                                       </label>
                                        <input class="w-65 form-control" id="adresse" v-model="clientForm.adresse"/>
                                    </div>
                                 </div>
                                
                             </div>
                              <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                 <div class="w-100 d-flex align-items-center my-2">
                                            <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                            <label for="tele" class="d-block m-0 text-right w-35 pr-2" >Telephone</label>
                                            <input class="w-65 form-control"  v-model="clientForm.telephone" type="text" id="tele"/>
                                        </div>
                                        </div>
                                 </div>
                                 <div class="col-6 my-2 d-flex flex-column align-items-center">
                                <div class="w-100 d-flex align-items-center my-2">
                                        <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                            <label for="email" class="d-block m-0 text-right w-35 pr-2" >Email</label>
                                            <input class="w-65 form-control"  v-model="clientForm.email" type="text" id="email"/>
                                        </div>
                                </div>
                                    
                                 </div>
                                  
                               
                             </div>
                             <div class="row">
                                <div class="col-6 my-2 d-flex flex-column align-items-center">
                                   <div class="w-100 my-2 d-flex flex-column">
                                        <div class="w-100 d-flex justify-content-between align-items-center">
                                            <label class="typo__label pt-0 d-block m-0 text-right w-35  pr-2 nowrap">Pays</label>
                                            <div class="w-65 p-0">
                                                   <select id="pays" v-model="clientForm.pays" class="w-100 form-control" :class="{ 'border-danger': submitted && !$v.clientForm.pays.required }" >
                                                       <option value="">Choisir Pays</option>
                                                       <option value="Afganistan">Afghanistan</option>
                                                       <option value="Albania">Albania</option>
                                                       <option value="Algeria">Algeria</option>
                                                       <option value="American Samoa">American Samoa</option>
                                                       <option value="Andorra">Andorra</option>
                                                       <option value="Angola">Angola</option>
                                                       <option value="Anguilla">Anguilla</option>
                                                       <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                                       <option value="Argentina">Argentina</option>
                                                       <option value="Armenia">Armenia</option>
                                                       <option value="Aruba">Aruba</option>
                                                       <option value="Australia">Australia</option>
                                                       <option value="Austria">Austria</option>
                                                       <option value="Azerbaijan">Azerbaijan</option>
                                                       <option value="Bahamas">Bahamas</option>
                                                       <option value="Bahrain">Bahrain</option>
                                                       <option value="Bangladesh">Bangladesh</option>
                                                       <option value="Barbados">Barbados</option>
                                                       <option value="Belarus">Belarus</option>
                                                       <option value="Belgium">Belgium</option>
                                                       <option value="Belize">Belize</option>
                                                       <option value="Benin">Benin</option>
                                                       <option value="Bermuda">Bermuda</option>
                                                       <option value="Bhutan">Bhutan</option>
                                                       <option value="Bolivia">Bolivia</option>
                                                       <option value="Bonaire">Bonaire</option>
                                                       <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                                       <option value="Botswana">Botswana</option>
                                                       <option value="Brazil">Brazil</option>
                                                       <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                                       <option value="Brunei">Brunei</option>
                                                       <option value="Bulgaria">Bulgaria</option>
                                                       <option value="Burkina Faso">Burkina Faso</option>
                                                       <option value="Burundi">Burundi</option>
                                                       <option value="Cambodia">Cambodia</option>
                                                       <option value="Cameroon">Cameroon</option>
                                                       <option value="Canada">Canada</option>
                                                       <option value="Canary Islands">Canary Islands</option>
                                                       <option value="Cape Verde">Cape Verde</option>
                                                       <option value="Cayman Islands">Cayman Islands</option>
                                                       <option value="Central African Republic">Central African Republic</option>
                                                       <option value="Chad">Chad</option>
                                                       <option value="Channel Islands">Channel Islands</option>
                                                       <option value="Chile">Chile</option>
                                                       <option value="China">China</option>
                                                       <option value="Christmas Island">Christmas Island</option>
                                                       <option value="Cocos Island">Cocos Island</option>
                                                       <option value="Colombia">Colombia</option>
                                                       <option value="Comoros">Comoros</option>
                                                       <option value="Congo">Congo</option>
                                                       <option value="Cook Islands">Cook Islands</option>
                                                       <option value="Costa Rica">Costa Rica</option>
                                                       <option value="Cote DIvoire">Cote DIvoire</option>
                                                       <option value="Croatia">Croatia</option>
                                                       <option value="Cuba">Cuba</option>
                                                       <option value="Curaco">Curacao</option>
                                                       <option value="Cyprus">Cyprus</option>
                                                       <option value="Czech Republic">Czech Republic</option>
                                                       <option value="Denmark">Denmark</option>
                                                       <option value="Djibouti">Djibouti</option>
                                                       <option value="Dominica">Dominica</option>
                                                       <option value="Dominican Republic">Dominican Republic</option>
                                                       <option value="East Timor">East Timor</option>
                                                       <option value="Ecuador">Ecuador</option>
                                                       <option value="Egypt">Egypt</option>
                                                       <option value="El Salvador">El Salvador</option>
                                                       <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                       <option value="Eritrea">Eritrea</option>
                                                       <option value="Estonia">Estonia</option>
                                                       <option value="Ethiopia">Ethiopia</option>
                                                       <option value="Falkland Islands">Falkland Islands</option>
                                                       <option value="Faroe Islands">Faroe Islands</option>
                                                       <option value="Fiji">Fiji</option>
                                                       <option value="Finland">Finland</option>
                                                       <option value="France">France</option>
                                                       <option value="French Guiana">French Guiana</option>
                                                       <option value="French Polynesia">French Polynesia</option>
                                                       <option value="French Southern Ter">French Southern Ter</option>
                                                       <option value="Gabon">Gabon</option>
                                                       <option value="Gambia">Gambia</option>
                                                       <option value="Georgia">Georgia</option>
                                                       <option value="Germany">Germany</option>
                                                       <option value="Ghana">Ghana</option>
                                                       <option value="Gibraltar">Gibraltar</option>
                                                       <option value="Great Britain">Great Britain</option>
                                                       <option value="Greece">Greece</option>
                                                       <option value="Greenland">Greenland</option>
                                                       <option value="Grenada">Grenada</option>
                                                       <option value="Guadeloupe">Guadeloupe</option>
                                                       <option value="Guam">Guam</option>
                                                       <option value="Guatemala">Guatemala</option>
                                                       <option value="Guinea">Guinea</option>
                                                       <option value="Guyana">Guyana</option>
                                                       <option value="Haiti">Haiti</option>
                                                       <option value="Hawaii">Hawaii</option>
                                                       <option value="Honduras">Honduras</option>
                                                       <option value="Hong Kong">Hong Kong</option>
                                                       <option value="Hungary">Hungary</option>
                                                       <option value="Iceland">Iceland</option>
                                                       <option value="Indonesia">Indonesia</option>
                                                       <option value="India">India</option>
                                                       <option value="Iran">Iran</option>
                                                       <option value="Iraq">Iraq</option>
                                                       <option value="Ireland">Ireland</option>
                                                       <option value="Isle of Man">Isle of Man</option>
                                                       <option value="Israel">Israel</option>
                                                       <option value="Italy">Italy</option>
                                                       <option value="Jamaica">Jamaica</option>
                                                       <option value="Japan">Japan</option>
                                                       <option value="Jordan">Jordan</option>
                                                       <option value="Kazakhstan">Kazakhstan</option>
                                                       <option value="Kenya">Kenya</option>
                                                       <option value="Kiribati">Kiribati</option>
                                                       <option value="Korea North">Korea North</option>
                                                       <option value="Korea Sout">Korea South</option>
                                                       <option value="Kuwait">Kuwait</option>
                                                       <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                       <option value="Laos">Laos</option>
                                                       <option value="Latvia">Latvia</option>
                                                       <option value="Lebanon">Lebanon</option>
                                                       <option value="Lesotho">Lesotho</option>
                                                       <option value="Liberia">Liberia</option>
                                                       <option value="Libya">Libya</option>
                                                       <option value="Liechtenstein">Liechtenstein</option>
                                                       <option value="Lithuania">Lithuania</option>
                                                       <option value="Luxembourg">Luxembourg</option>
                                                       <option value="Macau">Macau</option>
                                                       <option value="Macedonia">Macedonia</option>
                                                       <option value="Madagascar">Madagascar</option>
                                                       <option value="Malaysia">Malaysia</option>
                                                       <option value="Malawi">Malawi</option>
                                                       <option value="Maldives">Maldives</option>
                                                       <option value="Mali">Mali</option>
                                                       <option value="Malta">Malta</option>
                                                       <option value="Marshall Islands">Marshall Islands</option>
                                                       <option value="Martinique">Martinique</option>
                                                       <option value="Mauritania">Mauritania</option>
                                                       <option value="Mauritius">Mauritius</option>
                                                       <option value="Mayotte">Mayotte</option>
                                                       <option value="Mexico">Mexico</option>
                                                       <option value="Midway Islands">Midway Islands</option>
                                                       <option value="Moldova">Moldova</option>
                                                       <option value="Monaco">Monaco</option>
                                                       <option value="Mongolia">Mongolia</option>
                                                       <option value="Montserrat">Montserrat</option>
                                                       <option value="Morocco">Morocco</option>
                                                       <option value="Mozambique">Mozambique</option>
                                                       <option value="Myanmar">Myanmar</option>
                                                       <option value="Nambia">Nambia</option>
                                                       <option value="Nauru">Nauru</option>
                                                       <option value="Nepal">Nepal</option>
                                                       <option value="Netherland Antilles">Netherland Antilles</option>
                                                       <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                                       <option value="Nevis">Nevis</option>
                                                       <option value="New Caledonia">New Caledonia</option>
                                                       <option value="New Zealand">New Zealand</option>
                                                       <option value="Nicaragua">Nicaragua</option>
                                                       <option value="Niger">Niger</option>
                                                       <option value="Nigeria">Nigeria</option>
                                                       <option value="Niue">Niue</option>
                                                       <option value="Norfolk Island">Norfolk Island</option>
                                                       <option value="Norway">Norway</option>
                                                       <option value="Oman">Oman</option>
                                                       <option value="Pakistan">Pakistan</option>
                                                       <option value="Palau Island">Palau Island</option>
                                                       <option value="Palestine">Palestine</option>
                                                       <option value="Panama">Panama</option>
                                                       <option value="Papua New Guinea">Papua New Guinea</option>
                                                       <option value="Paraguay">Paraguay</option>
                                                       <option value="Peru">Peru</option>
                                                       <option value="Phillipines">Philippines</option>
                                                       <option value="Pitcairn Island">Pitcairn Island</option>
                                                       <option value="Poland">Poland</option>
                                                       <option value="Portugal">Portugal</option>
                                                       <option value="Puerto Rico">Puerto Rico</option>
                                                       <option value="Qatar">Qatar</option>
                                                       <option value="Republic of Montenegro">Republic of Montenegro</option>
                                                       <option value="Republic of Serbia">Republic of Serbia</option>
                                                       <option value="Reunion">Reunion</option>
                                                       <option value="Romania">Romania</option>
                                                       <option value="Russia">Russia</option>
                                                       <option value="Rwanda">Rwanda</option>
                                                       <option value="St Barthelemy">St Barthelemy</option>
                                                       <option value="St Eustatius">St Eustatius</option>
                                                       <option value="St Helena">St Helena</option>
                                                       <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                                       <option value="St Lucia">St Lucia</option>
                                                       <option value="St Maarten">St Maarten</option>
                                                       <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                                       <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                                       <option value="Saipan">Saipan</option>
                                                       <option value="Samoa">Samoa</option>
                                                       <option value="Samoa American">Samoa American</option>
                                                       <option value="San Marino">San Marino</option>
                                                       <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                                       <option value="Saudi Arabia">Saudi Arabia</option>
                                                       <option value="Senegal">Senegal</option>
                                                       <option value="Seychelles">Seychelles</option>
                                                       <option value="Sierra Leone">Sierra Leone</option>
                                                       <option value="Singapore">Singapore</option>
                                                       <option value="Slovakia">Slovakia</option>
                                                       <option value="Slovenia">Slovenia</option>
                                                       <option value="Solomon Islands">Solomon Islands</option>
                                                       <option value="Somalia">Somalia</option>
                                                       <option value="South Africa">South Africa</option>
                                                       <option value="Spain">Spain</option>
                                                       <option value="Sri Lanka">Sri Lanka</option>
                                                       <option value="Sudan">Sudan</option>
                                                       <option value="Suriname">Suriname</option>
                                                       <option value="Swaziland">Swaziland</option>
                                                       <option value="Sweden">Sweden</option>
                                                       <option value="Switzerland">Switzerland</option>
                                                       <option value="Syria">Syria</option>
                                                       <option value="Tahiti">Tahiti</option>
                                                       <option value="Taiwan">Taiwan</option>
                                                       <option value="Tajikistan">Tajikistan</option>
                                                       <option value="Tanzania">Tanzania</option>
                                                       <option value="Thailand">Thailand</option>
                                                       <option value="Togo">Togo</option>
                                                       <option value="Tokelau">Tokelau</option>
                                                       <option value="Tonga">Tonga</option>
                                                       <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                                       <option value="Tunisia">Tunisia</option>
                                                       <option value="Turkey">Turkey</option>
                                                       <option value="Turkmenistan">Turkmenistan</option>
                                                       <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                                       <option value="Tuvalu">Tuvalu</option>
                                                       <option value="Uganda">Uganda</option>
                                                       <option value="United Kingdom">United Kingdom</option>
                                                       <option value="Ukraine">Ukraine</option>
                                                       <option value="United Arab Erimates">United Arab Emirates</option>
                                                       <option value="United States of America">United States of America</option>
                                                       <option value="Uraguay">Uruguay</option>
                                                       <option value="Uzbekistan">Uzbekistan</option>
                                                       <option value="Vanuatu">Vanuatu</option>
                                                       <option value="Vatican City State">Vatican City State</option>
                                                       <option value="Venezuela">Venezuela</option>
                                                       <option value="Vietnam">Vietnam</option>
                                                       <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                                       <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                                       <option value="Wake Island">Wake Island</option>
                                                       <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                                       <option value="Yemen">Yemen</option>
                                                       <option value="Zaire">Zaire</option>
                                                       <option value="Zambia">Zambia</option>
                                                       <option value="Zimbabwe">Zimbabwe</option>
                                                    </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 my-2 d-flex flex-column align-items-center"></div>  
                             </div>
                            <div class="row">
                                  <div class="col-6 my-2 d-flex flex-column">
                                       <div class="w-100 d-flex my-2">
                                            <div class="md-form w-100 d-flex justify-content-between align-items-center">
                                            <label for="logo" class="d-block m-0 text-right w-35 pr-2" >Logo</label>
                                            <div class="w-65 p-0">
                                            <input class="form-control"  :key="fileInputKey" ref="file" v-on:change="handleFileUpload()" type="file" id="logo" :disabled = "hasImage"/>
                                           
                                             </div>
                                            </div>

                                        </div>
                                        <div class="w-100 d-flex align-items-center my-2"></div>
                                    
                                 </div>
                                 <div class="col-6  d-flex justify-content-start">
                                       <template v-if="modeModify && hasImage">
                                            <img :src="'/images/logo/'+clientForm.logo" class="mt-1 rounded-lg" height="80" />
                                            <button class="ml-3 btn p-0 text-danger" v-on:click="removeImage()">
                                                <i aria-hidden="true" class="fa fa-close text-danger"></i> Supprimer le logo</button>
                                        </template>
                                        <template v-if="clientForm.urlPreview != ''">
                                        <img height="80" :src="clientForm.urlPreview" >
                                         <button class="ml-3 btn p-0 text-danger" v-on:click="removeImagePreview()">
                                                <i aria-hidden="true" class="fa fa-close text-danger"></i> Supprimer le logo</button>
                                        </template>
                                 </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12 my-2 d-flex flex-column">
                                    <div class="w-100 d-flex justify-content-between flex-column align-items-center">
                                        <label class="typo__label d-block m-0 w-100  pr-2 nowrap">Fournisseur</label>
                                        <div class="w-100 p-0">
                                            <multiselect v-model="clientForm.value" :options="listFournisseurs" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Choisir" label="fonmfo" track-by="id" :preselect-first="false"  :class="{ 'border-danger': submitted && !$v.clientForm.value.required }">
                                                <template slot="selection" slot-scope="{ values, search, isOpen }">
                                                    <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} fournisseur(s) selectionné(s)</span> 
                                                </template>
                                            </multiselect>
                                        </div>
                                    </div>
                                </div>
                             </div>
                             <div class="row">  
                                 <div class="col-12 my-2 d-flex flex-column">
                                    <div class="w-100 d-flex justify-content-between flex-column align-items-center">
                                        <label class="typo__label d-block m-0 w-100  pr-2 nowrap">Type Commande</label>
                                        <div class="w-100 p-0">
                                            <multiselect v-model="clientForm.valueTypeCmd" :options="listTypeCmds" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Choisir" label="typcmd" track-by="id" :preselect-first="false" :class="{ 'border-danger': submitted && !$v.clientForm.valueTypeCmd.required }">
                                                <template slot="selection" slot-scope="{ values, search, isOpen }">
                                                    <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} Type Commande(s) selectionné(s)</span> 
                                                </template>
                                            </multiselect>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                             <div class="modal-footer d-flex justify-content-center mt-4"> 

                                <template v-if="modeModify">
                                        <button type="submit" class="btn btn-success">Modifier</button>
                                        <button type="button" v-on:click="closeModal()" class="btn btn-warning">Annuler la modification</button>
                                </template>
                                <template v-else>
                                    <button type="submit" class="btn btn-success" :disabled="loading?true:false">
                                        <template v-if="loading">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        </template>
                                        Enregister
                                    </button>
                                    <button type="button" v-on:click="closeModal()" class="btn btn-warning">Annuler</button>
                                </template>
                               
                            </div>
                         </form>
                    </div>
                    
             </div>
            
          </div>
        </div>
        
    </div>
</template>
<script>
    import { required, minLength, between } from 'vuelidate/lib/validators';
    import Multiselect from 'vue-multiselect';
    import PageLoader from '../../components/PageLoader.vue';     

    export default {
        props: [
        ],
         components: {
            Multiselect,
            PageLoader
          },
        data() { 
            return {
                clientForm :{
                    id: '',
                    nom: '',
                    email: '',
                    adresse: '',
                    logo: '',
                    telephone: '',
                    idFournisseurs: [],
                    idTypeCmd: [],  
                    pays: '',
                    value: [],
                    valueTypeCmd: [],
                    urlPreview:''
                },
                hasImage: false,
                submitted: false,
                clients: {},
                paginate: window.pagination,
                modeModify: false,

                
                /*fournisseurs: [], 
                typeCmd: [],*/

                listFournisseurs: [],
                listTypeCmds: [],
                isLoading: true,
                fileInputKey: 0,
                loading: false
            }

        },
        validations: {
            clientForm : {
                nom:  { required },
                pays: { required },
                value:{ required },
                valueTypeCmd: { required },
                pays: { required }
            },
            
        },
         watch: {
           paginate: function(){

                this.getClient();
           }
        },
         methods : { 
            removeImage(){
                this.hasImage = false;
                this.clientForm.logo = "";
            },
            removeImagePreview(){
                 this.clientForm.logo = "";
                 this.clientForm.urlPreview = "";
                 this.fileInputKey++;
            },
            handleFileUpload(){

                this.clientForm.logo = this.$refs.file.files[0];
                this.clientForm.urlPreview = URL.createObjectURL(this.$refs.file.files[0]);
               
            },
            saveClient(){
                this.submitted = true;
                // stop here if form is invalid
                this.$v.clientForm.$touch();
                if (this.$v.clientForm.$invalid) {
                    return;
                }
                
                const data = new FormData();
                data.append('file', this.clientForm.logo);
                data.append('nom', this.clientForm.nom);
                data.append('email', this.clientForm.email); 
                data.append('adresse', this.clientForm.adresse);
                data.append('telephone', this.clientForm.telephone);
                data.append('pays', this.clientForm.pays);
                
                let action = "createClient"; 

                if(this.modeModify){
                    data.append('id', this.clientForm.id);
                    if(this.hasImage){
                        data.append('imageSet', this.clientForm.logo);
                    }
                    action = "modifyClient";
                }

                this.clientForm.idFournisseurs = [];
                this.clientForm.idTypeCmd = [];

                // get fournisseurs
                for(var i=0; i<this.clientForm.value.length; i++){
                    var item = this.clientForm.value[i];
                    this.clientForm.idFournisseurs.push(item.id);  
                }
                // get type Cmd 
                for(var i=0; i<this.clientForm.valueTypeCmd.length; i++){
                    var item = this.clientForm.valueTypeCmd[i];
                    this.clientForm.idTypeCmd.push(item.id);  
                }
             
                data.append('fournisseurs', JSON.stringify(this.clientForm.idFournisseurs));
                data.append('typecmd', JSON.stringify(this.clientForm.idTypeCmd));

                this.loading=true;

                axios.post("/configuration/"+action, data).then(response => {
                  
                    if(response.data.code==0){
                        this.$refs.closePoup.click();
                        this.getClient();
                        this.flushData();
                        Vue.swal.fire(
                          'succés!',
                          'Client enregistré avec succés!',
                          'success'
                        )
                        

                    }else{
                         Vue.swal.fire(
                          'error!',
                          response.data.message,
                          'error'
                        )
                    }
                    this.submitted = false;
                    this.loading = false;
                    this.removeImagePreview();
                   
                });
            }, 
            flushData(){
                this.clientForm.id = '',
                this.clientForm.nom = '';
                this.clientForm.email = '';
                this.clientForm.adresse = '';
                this.clientForm.logo = '';
                this.clientForm.telephone = '';
                this.clientForm.idFournisseurs = [];
                this.clientForm.pays = '',
                this.clientForm.value = [];
                this.clientForm.valueTypeCmd = []; 
            },
            getClient(page = 1){
                this.isLoading=true;
                axios.get('/configuration/getClient?page=' + page + "&paginate=" + this.paginate).then(response => {
                    this.clients = response.data;
                    this.closeLoader();
                });
            },
            getConfig(){
                axios.get('/configuration/config').then(response => {
                   this.listTypeCmds=response.data.typeCmd;
                   this.listFournisseurs=response.data.fournis;
                   this.closeLoader();
                });
            },
            deleteClient(client){
                Vue.swal.fire({
                  title: 'Confirmez la suppression',
                  text: client.nom,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                  if (result.isConfirmed) {
                        axios.delete('/configuration/deleteClient/'+client.id+"?logo="+client.logo).then(response => {
                            console.log(response);
                             Vue.swal.fire(
                              'Supprimé!',
                              'Client supprimé avec succés.',
                              'success'
                            );
                             this.modeModify = false;
                             this.getClient();


                        });
                  
                  }
                })
            },
            editClient(client){

                this.modeModify = true;
                this.submitted = false;
                this.clientForm.id = client.id;
                this.clientForm.nom = client.nom;
                this.clientForm.adresse = client.adresse;
                this.clientForm.telephone= client.telephone;
                this.clientForm.logo= client.logo;
                this.clientForm.email= client.email;
                this.clientForm.pays= client.pays;
                if(client.logo){
                    this.hasImage = true;
                }else{
                    this.hasImage = false;
                }
                // value selected fournisseur
                this.clientForm.value=[];
                var selected = [];
                for(var i=0; i<client.fournisseurs.length;i++){
                    selected.push(this.listFournisseurs.find(option => option.id === client.fournisseurs[i]));
                }
                this.clientForm.value = selected;

                // value selected fournisseur

                this.clientForm.valueTypeCmd=[];
                var selected2 = [];
                for(var i=0; i<client.typeCmd.length;i++){
                    selected2.push(this.listTypeCmds.find(option => option.id === client.typeCmd[i]));
                }
                this.clientForm.valueTypeCmd = selected2;
               
            },
            closeModal(){
                this.$refs.closePoup.click();
                this.flushData();
                this.submitted = false;
                this.modeModify = false;
                this.hasImage = false;
                this.flushData();
               

            },
            closeLoader(){
                var thiss = this;
                setTimeout(function(){
                  thiss.isLoading=false;
                },500);
            }
        },
        mounted() {
            this.getClient();
            this.getConfig();
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>