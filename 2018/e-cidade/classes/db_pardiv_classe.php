<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */
//MODULO: divida
//CLASSE DA ENTIDADE pardiv
class cl_pardiv { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $ultcoddiv = 0; 
   var $codbco = 0; 
   var $codage = null; 
   var $ultcertid = 0; 
   var $qv = 'f'; 
   var $inflat = null; 
   var $ultparcel = 0; 
   var $k00_hist = 0; 
   var $v04_recparini = 0; 
   var $v04_docum = 0; 
   var $v04_histpadparc = 0; 
   var $v04_peticaoinicial = 0; 
   var $v04_ordemendcda = 0; 
   var $v04_histjuros = 0; 
   var $v04_instit = 0; 
   var $v04_tipoinicial = 0; 
   var $v04_tipocertidao = 0; 
   var $v04_envolcdaiptu = 0; 
   var $v04_envolcdaiss = 0; 
   var $v04_envolprinciptu = 'f'; 
   var $v04_imphistcda = 'f'; 
   var $v04_expfalecimentocda = null; 
   var $v04_implivrofolha = 'f'; 
   var $v04_confexpfalec = 0; 
   var $v04_formgeracda = 0; 
   var $v04_cobrarjurosmultacda = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ultcoddiv = int4 = ultimo codigo da divida ativa 
                 codbco = int4 = codigo do banco 
                 codage = char(5) = codigo da agencia 
                 ultcertid = int4 = numero da ultima certidao 
                 qv = bool = imprime quantidade? ou valor? (no termo) 
                 inflat = varchar(5) = inflator 
                 ultparcel = int4 = numero do ultimo parcelamento 
                 k00_hist = int4 = Histórico de Cálculo 
                 v04_recparini = int4 = Receita para parcelamento de inicial 
                 v04_docum = int4 = Código 
                 v04_histpadparc = int4 = Historico padrao para parcelamentos 
                 v04_peticaoinicial = int4 = Modelo de petição inicial 
                 v04_ordemendcda = int4 = Ordem do endereço na CDA 
                 v04_histjuros = int4 = Histórico de cálculo dos juros 
                 v04_instit = int4 = Cod. Instituição 
                 v04_tipoinicial = int4 = Tipo de Debito 
                 v04_tipocertidao = int4 = Tipo de Debito 
                 v04_envolcdaiptu = int4 = Envolvidos na CDA (Imóvel) 
                 v04_envolcdaiss = int4 = Envolvidos na CDA (Empresa) 
                 v04_envolprinciptu = bool = Imprimir Somente Principais 
                 v04_imphistcda = bool = Imprimir Histórico/Observações na CDA 
                 v04_expfalecimentocda = varchar(50) = Expressão antes do nome para falecidos 
                 v04_implivrofolha = bool = Imprime Livro/Folha na CDA 
                 v04_confexpfalec = int4 = Utilizar Expressão antes do Nome p/ Falecidos 
                 v04_formgeracda = int4 = Forma de Geração de CDA 
                 v04_cobrarjurosmultacda = bool = Juros e multa até o vencimento do recibo 
                 ";
   //funcao construtor da classe 
   function cl_pardiv() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pardiv"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->ultcoddiv = ($this->ultcoddiv == ""?@$GLOBALS["HTTP_POST_VARS"]["ultcoddiv"]:$this->ultcoddiv);
       $this->codbco = ($this->codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["codbco"]:$this->codbco);
       $this->codage = ($this->codage == ""?@$GLOBALS["HTTP_POST_VARS"]["codage"]:$this->codage);
       $this->ultcertid = ($this->ultcertid == ""?@$GLOBALS["HTTP_POST_VARS"]["ultcertid"]:$this->ultcertid);
       $this->qv = ($this->qv == "f"?@$GLOBALS["HTTP_POST_VARS"]["qv"]:$this->qv);
       $this->inflat = ($this->inflat == ""?@$GLOBALS["HTTP_POST_VARS"]["inflat"]:$this->inflat);
       $this->ultparcel = ($this->ultparcel == ""?@$GLOBALS["HTTP_POST_VARS"]["ultparcel"]:$this->ultparcel);
       $this->k00_hist = ($this->k00_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist"]:$this->k00_hist);
       $this->v04_recparini = ($this->v04_recparini == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_recparini"]:$this->v04_recparini);
       $this->v04_docum = ($this->v04_docum == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_docum"]:$this->v04_docum);
       $this->v04_histpadparc = ($this->v04_histpadparc == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_histpadparc"]:$this->v04_histpadparc);
       $this->v04_peticaoinicial = ($this->v04_peticaoinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_peticaoinicial"]:$this->v04_peticaoinicial);
       $this->v04_ordemendcda = ($this->v04_ordemendcda == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_ordemendcda"]:$this->v04_ordemendcda);
       $this->v04_histjuros = ($this->v04_histjuros == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_histjuros"]:$this->v04_histjuros);
       $this->v04_instit = ($this->v04_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_instit"]:$this->v04_instit);
       $this->v04_tipoinicial = ($this->v04_tipoinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_tipoinicial"]:$this->v04_tipoinicial);
       $this->v04_tipocertidao = ($this->v04_tipocertidao == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_tipocertidao"]:$this->v04_tipocertidao);
       $this->v04_envolcdaiptu = ($this->v04_envolcdaiptu == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_envolcdaiptu"]:$this->v04_envolcdaiptu);
       $this->v04_envolcdaiss = ($this->v04_envolcdaiss == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_envolcdaiss"]:$this->v04_envolcdaiss);
       $this->v04_envolprinciptu = ($this->v04_envolprinciptu == "f"?@$GLOBALS["HTTP_POST_VARS"]["v04_envolprinciptu"]:$this->v04_envolprinciptu);
       $this->v04_imphistcda = ($this->v04_imphistcda == "f"?@$GLOBALS["HTTP_POST_VARS"]["v04_imphistcda"]:$this->v04_imphistcda);
       $this->v04_expfalecimentocda = ($this->v04_expfalecimentocda == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_expfalecimentocda"]:$this->v04_expfalecimentocda);
       $this->v04_implivrofolha = ($this->v04_implivrofolha == "f"?@$GLOBALS["HTTP_POST_VARS"]["v04_implivrofolha"]:$this->v04_implivrofolha);
       $this->v04_confexpfalec = ($this->v04_confexpfalec == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_confexpfalec"]:$this->v04_confexpfalec);
       $this->v04_formgeracda = ($this->v04_formgeracda == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_formgeracda"]:$this->v04_formgeracda);
       $this->v04_cobrarjurosmultacda = ($this->v04_cobrarjurosmultacda == "f"?@$GLOBALS["HTTP_POST_VARS"]["v04_cobrarjurosmultacda"]:$this->v04_cobrarjurosmultacda);
     }else{
       $this->v04_instit = ($this->v04_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["v04_instit"]:$this->v04_instit);
     }
   }
   // funcao para Inclusão
   function incluir ($v04_instit){ 
      $this->atualizacampos();
     if($this->ultcoddiv == null ){ 
       $this->ultcoddiv = "0";
     }
     if($this->codbco == null ){ 
       $this->codbco = "0";
     }
     if($this->codage == null ){ 
       $this->codage = "00000";
     }
     if($this->ultcertid == null ){ 
       $this->ultcertid = "0";
     }
     if($this->qv == null ){ 
       $this->qv = "f";
     }
     if($this->ultparcel == null ){ 
       $this->ultparcel = "0";
     }
     if($this->k00_hist == null ){ 
       $this->k00_hist = "null";
     }
     if($this->v04_recparini == null ){ 
       $this->v04_recparini = "0";
     }
     if($this->v04_docum == null ){ 
       $this->erro_sql = " Campo Código não informado.";
       $this->erro_campo = "v04_docum";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v04_histpadparc == null ){ 
       $this->v04_histpadparc = "0";
     }
     if($this->v04_peticaoinicial == null ){ 
       $this->erro_sql = " Campo Modelo de petição inicial não informado.";
       $this->erro_campo = "v04_peticaoinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v04_ordemendcda == null ){ 
       $this->erro_sql = " Campo Ordem do endereço na CDA não informado.";
       $this->erro_campo = "v04_ordemendcda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v04_histjuros == null ){ 
       $this->erro_sql = " Campo Histórico de cálculo dos juros não informado.";
       $this->erro_campo = "v04_histjuros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v04_tipoinicial == null ){ 
       $this->erro_sql = " Campo Tipo de Debito não informado.";
       $this->erro_campo = "v04_tipoinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v04_tipocertidao == null ){ 
       $this->erro_sql = " Campo Tipo de Debito não informado.";
       $this->erro_campo = "v04_tipocertidao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v04_envolcdaiptu == null ){ 
       $this->erro_sql = " Campo Envolvidos na CDA (Imóvel) não informado.";
       $this->erro_campo = "v04_envolcdaiptu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v04_envolcdaiss == null ){ 
       $this->erro_sql = " Campo Envolvidos na CDA (Empresa) não informado.";
       $this->erro_campo = "v04_envolcdaiss";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v04_envolprinciptu == null ){ 
       $this->erro_sql = " Campo Imprimir Somente Principais não informado.";
       $this->erro_campo = "v04_envolprinciptu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v04_imphistcda == null ){ 
       $this->erro_sql = " Campo Imprimir Histórico/Observações na CDA não informado.";
       $this->erro_campo = "v04_imphistcda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v04_implivrofolha == null ){ 
       $this->erro_sql = " Campo Imprime Livro/Folha na CDA não informado.";
       $this->erro_campo = "v04_implivrofolha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v04_confexpfalec == null ){ 
       $this->erro_sql = " Campo Utilizar Expressão antes do Nome p/ Falecidos não informado.";
       $this->erro_campo = "v04_confexpfalec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v04_formgeracda == null ){ 
       $this->erro_sql = " Campo Forma de Geração de CDA não informado.";
       $this->erro_campo = "v04_formgeracda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v04_cobrarjurosmultacda == null ){ 
       $this->erro_sql = " Campo Juros e multa até o vencimento do recibo não informado.";
       $this->erro_campo = "v04_cobrarjurosmultacda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->v04_instit = $v04_instit; 
     if(($this->v04_instit == null) || ($this->v04_instit == "") ){ 
       $this->erro_sql = " Campo v04_instit não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pardiv(
                                       ultcoddiv 
                                      ,codbco 
                                      ,codage 
                                      ,ultcertid 
                                      ,qv 
                                      ,inflat 
                                      ,ultparcel 
                                      ,k00_hist 
                                      ,v04_recparini 
                                      ,v04_docum 
                                      ,v04_histpadparc 
                                      ,v04_peticaoinicial 
                                      ,v04_ordemendcda 
                                      ,v04_histjuros 
                                      ,v04_instit 
                                      ,v04_tipoinicial 
                                      ,v04_tipocertidao 
                                      ,v04_envolcdaiptu 
                                      ,v04_envolcdaiss 
                                      ,v04_envolprinciptu 
                                      ,v04_imphistcda 
                                      ,v04_expfalecimentocda 
                                      ,v04_implivrofolha 
                                      ,v04_confexpfalec 
                                      ,v04_formgeracda 
                                      ,v04_cobrarjurosmultacda 
                       )
                values (
                                $this->ultcoddiv 
                               ,$this->codbco 
                               ,'$this->codage' 
                               ,$this->ultcertid 
                               ,'$this->qv' 
                               ,'$this->inflat' 
                               ,$this->ultparcel 
                               ,$this->k00_hist 
                               ,$this->v04_recparini 
                               ,$this->v04_docum 
                               ,$this->v04_histpadparc 
                               ,$this->v04_peticaoinicial 
                               ,$this->v04_ordemendcda 
                               ,$this->v04_histjuros 
                               ,$this->v04_instit 
                               ,$this->v04_tipoinicial 
                               ,$this->v04_tipocertidao 
                               ,$this->v04_envolcdaiptu 
                               ,$this->v04_envolcdaiss 
                               ,'$this->v04_envolprinciptu' 
                               ,'$this->v04_imphistcda' 
                               ,'$this->v04_expfalecimentocda' 
                               ,'$this->v04_implivrofolha' 
                               ,$this->v04_confexpfalec 
                               ,$this->v04_formgeracda 
                               ,'$this->v04_cobrarjurosmultacda' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "parametros da divida ($this->v04_instit) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "parametros da divida já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "parametros da divida ($this->v04_instit) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v04_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v04_instit  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10573,'$this->v04_instit','I')");
         $resac = db_query("insert into db_acount values($acount,358,2270,'','".AddSlashes(pg_result($resaco,0,'ultcoddiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,486,'','".AddSlashes(pg_result($resaco,0,'codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,2271,'','".AddSlashes(pg_result($resaco,0,'codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,2272,'','".AddSlashes(pg_result($resaco,0,'ultcertid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,2273,'','".AddSlashes(pg_result($resaco,0,'qv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,507,'','".AddSlashes(pg_result($resaco,0,'inflat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,2274,'','".AddSlashes(pg_result($resaco,0,'ultparcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,375,'','".AddSlashes(pg_result($resaco,0,'k00_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,2428,'','".AddSlashes(pg_result($resaco,0,'v04_recparini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,5711,'','".AddSlashes(pg_result($resaco,0,'v04_docum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,8864,'','".AddSlashes(pg_result($resaco,0,'v04_histpadparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,9567,'','".AddSlashes(pg_result($resaco,0,'v04_peticaoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,9644,'','".AddSlashes(pg_result($resaco,0,'v04_ordemendcda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,9676,'','".AddSlashes(pg_result($resaco,0,'v04_histjuros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,10573,'','".AddSlashes(pg_result($resaco,0,'v04_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,10751,'','".AddSlashes(pg_result($resaco,0,'v04_tipoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,10754,'','".AddSlashes(pg_result($resaco,0,'v04_tipocertidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,11767,'','".AddSlashes(pg_result($resaco,0,'v04_envolcdaiptu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,11768,'','".AddSlashes(pg_result($resaco,0,'v04_envolcdaiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,11769,'','".AddSlashes(pg_result($resaco,0,'v04_envolprinciptu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,11770,'','".AddSlashes(pg_result($resaco,0,'v04_imphistcda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,14493,'','".AddSlashes(pg_result($resaco,0,'v04_expfalecimentocda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,14829,'','".AddSlashes(pg_result($resaco,0,'v04_implivrofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,15556,'','".AddSlashes(pg_result($resaco,0,'v04_confexpfalec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,17084,'','".AddSlashes(pg_result($resaco,0,'v04_formgeracda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,358,21701,'','".AddSlashes(pg_result($resaco,0,'v04_cobrarjurosmultacda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($v04_instit=null) { 
      $this->atualizacampos();
     $sql = " update pardiv set ";
     $virgula = "";
     if(trim($this->ultcoddiv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ultcoddiv"])){ 
        if(trim($this->ultcoddiv)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ultcoddiv"])){ 
           $this->ultcoddiv = "0" ; 
        } 
       $sql  .= $virgula." ultcoddiv = $this->ultcoddiv ";
       $virgula = ",";
     }
     if(trim($this->codbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codbco"])){ 
        if(trim($this->codbco)=="" && isset($GLOBALS["HTTP_POST_VARS"]["codbco"])){ 
           $this->codbco = "0" ; 
        } 
       $sql  .= $virgula." codbco = $this->codbco ";
       $virgula = ",";
     }
     if(trim($this->codage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codage"])){ 
       $sql  .= $virgula." codage = '$this->codage' ";
       $virgula = ",";
     }
     if(trim($this->ultcertid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ultcertid"])){ 
        if(trim($this->ultcertid)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ultcertid"])){ 
           $this->ultcertid = "0" ; 
        } 
       $sql  .= $virgula." ultcertid = $this->ultcertid ";
       $virgula = ",";
     }
     if(trim($this->qv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["qv"])){ 
       $sql  .= $virgula." qv = '$this->qv' ";
       $virgula = ",";
     }
     if(trim($this->inflat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["inflat"])){ 
       $sql  .= $virgula." inflat = '$this->inflat' ";
       $virgula = ",";
     }
     if(trim($this->ultparcel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ultparcel"])){ 
        if(trim($this->ultparcel)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ultparcel"])){ 
           $this->ultparcel = "0" ; 
        } 
       $sql  .= $virgula." ultparcel = $this->ultparcel ";
       $virgula = ",";
     }
     if(trim($this->k00_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist"])){ 
       $sql  .= $virgula." k00_hist = $this->k00_hist ";
       $virgula = ",";
     }
     if(trim($this->v04_recparini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_recparini"])){ 
        if(trim($this->v04_recparini)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v04_recparini"])){ 
           $this->v04_recparini = "0" ; 
        } 
       $sql  .= $virgula." v04_recparini = $this->v04_recparini ";
       $virgula = ",";
     }
     if(trim($this->v04_docum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_docum"])){ 
       $sql  .= $virgula." v04_docum = $this->v04_docum ";
       $virgula = ",";
       if(trim($this->v04_docum) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "v04_docum";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v04_histpadparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_histpadparc"])){ 
        if(trim($this->v04_histpadparc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v04_histpadparc"])){ 
           $this->v04_histpadparc = "0" ; 
        } 
       $sql  .= $virgula." v04_histpadparc = $this->v04_histpadparc ";
       $virgula = ",";
     }
     if(trim($this->v04_peticaoinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_peticaoinicial"])){ 
       $sql  .= $virgula." v04_peticaoinicial = $this->v04_peticaoinicial ";
       $virgula = ",";
       if(trim($this->v04_peticaoinicial) == null ){ 
         $this->erro_sql = " Campo Modelo de petição inicial não informado.";
         $this->erro_campo = "v04_peticaoinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v04_ordemendcda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_ordemendcda"])){ 
       $sql  .= $virgula." v04_ordemendcda = $this->v04_ordemendcda ";
       $virgula = ",";
       if(trim($this->v04_ordemendcda) == null ){ 
         $this->erro_sql = " Campo Ordem do endereço na CDA não informado.";
         $this->erro_campo = "v04_ordemendcda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v04_histjuros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_histjuros"])){ 
       $sql  .= $virgula." v04_histjuros = $this->v04_histjuros ";
       $virgula = ",";
       if(trim($this->v04_histjuros) == null ){ 
         $this->erro_sql = " Campo Histórico de cálculo dos juros não informado.";
         $this->erro_campo = "v04_histjuros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v04_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_instit"])){ 
       $sql  .= $virgula." v04_instit = $this->v04_instit ";
       $virgula = ",";
       if(trim($this->v04_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição não informado.";
         $this->erro_campo = "v04_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v04_tipoinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_tipoinicial"])){ 
       $sql  .= $virgula." v04_tipoinicial = $this->v04_tipoinicial ";
       $virgula = ",";
       if(trim($this->v04_tipoinicial) == null ){ 
         $this->erro_sql = " Campo Tipo de Debito não informado.";
         $this->erro_campo = "v04_tipoinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v04_tipocertidao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_tipocertidao"])){ 
       $sql  .= $virgula." v04_tipocertidao = $this->v04_tipocertidao ";
       $virgula = ",";
       if(trim($this->v04_tipocertidao) == null ){ 
         $this->erro_sql = " Campo Tipo de Debito não informado.";
         $this->erro_campo = "v04_tipocertidao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v04_envolcdaiptu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_envolcdaiptu"])){ 
       $sql  .= $virgula." v04_envolcdaiptu = $this->v04_envolcdaiptu ";
       $virgula = ",";
       if(trim($this->v04_envolcdaiptu) == null ){ 
         $this->erro_sql = " Campo Envolvidos na CDA (Imóvel) não informado.";
         $this->erro_campo = "v04_envolcdaiptu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v04_envolcdaiss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_envolcdaiss"])){ 
       $sql  .= $virgula." v04_envolcdaiss = $this->v04_envolcdaiss ";
       $virgula = ",";
       if(trim($this->v04_envolcdaiss) == null ){ 
         $this->erro_sql = " Campo Envolvidos na CDA (Empresa) não informado.";
         $this->erro_campo = "v04_envolcdaiss";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v04_envolprinciptu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_envolprinciptu"])){ 
       $sql  .= $virgula." v04_envolprinciptu = '$this->v04_envolprinciptu' ";
       $virgula = ",";
       if(trim($this->v04_envolprinciptu) == null ){ 
         $this->erro_sql = " Campo Imprimir Somente Principais não informado.";
         $this->erro_campo = "v04_envolprinciptu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v04_imphistcda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_imphistcda"])){ 
       $sql  .= $virgula." v04_imphistcda = '$this->v04_imphistcda' ";
       $virgula = ",";
       if(trim($this->v04_imphistcda) == null ){ 
         $this->erro_sql = " Campo Imprimir Histórico/Observações na CDA não informado.";
         $this->erro_campo = "v04_imphistcda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v04_expfalecimentocda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_expfalecimentocda"])){ 
       $sql  .= $virgula." v04_expfalecimentocda = '$this->v04_expfalecimentocda' ";
       $virgula = ",";
     }
     if(trim($this->v04_implivrofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_implivrofolha"])){ 
       $sql  .= $virgula." v04_implivrofolha = '$this->v04_implivrofolha' ";
       $virgula = ",";
       if(trim($this->v04_implivrofolha) == null ){ 
         $this->erro_sql = " Campo Imprime Livro/Folha na CDA não informado.";
         $this->erro_campo = "v04_implivrofolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v04_confexpfalec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_confexpfalec"])){ 
       $sql  .= $virgula." v04_confexpfalec = $this->v04_confexpfalec ";
       $virgula = ",";
       if(trim($this->v04_confexpfalec) == null ){ 
         $this->erro_sql = " Campo Utilizar Expressão antes do Nome p/ Falecidos não informado.";
         $this->erro_campo = "v04_confexpfalec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v04_formgeracda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_formgeracda"])){ 
       $sql  .= $virgula." v04_formgeracda = $this->v04_formgeracda ";
       $virgula = ",";
       if(trim($this->v04_formgeracda) == null ){ 
         $this->erro_sql = " Campo Forma de Geração de CDA não informado.";
         $this->erro_campo = "v04_formgeracda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v04_cobrarjurosmultacda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v04_cobrarjurosmultacda"])){ 
       $sql  .= $virgula." v04_cobrarjurosmultacda = '$this->v04_cobrarjurosmultacda' ";
       $virgula = ",";
       if(trim($this->v04_cobrarjurosmultacda) == null ){ 
         $this->erro_sql = " Campo Juros e multa até o vencimento do recibo não informado.";
         $this->erro_campo = "v04_cobrarjurosmultacda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v04_instit!=null){
       $sql .= " v04_instit = $this->v04_instit";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v04_instit));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,10573,'$this->v04_instit','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ultcoddiv"]) || $this->ultcoddiv != "")
             $resac = db_query("insert into db_acount values($acount,358,2270,'".AddSlashes(pg_result($resaco,$conresaco,'ultcoddiv'))."','$this->ultcoddiv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["codbco"]) || $this->codbco != "")
             $resac = db_query("insert into db_acount values($acount,358,486,'".AddSlashes(pg_result($resaco,$conresaco,'codbco'))."','$this->codbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["codage"]) || $this->codage != "")
             $resac = db_query("insert into db_acount values($acount,358,2271,'".AddSlashes(pg_result($resaco,$conresaco,'codage'))."','$this->codage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ultcertid"]) || $this->ultcertid != "")
             $resac = db_query("insert into db_acount values($acount,358,2272,'".AddSlashes(pg_result($resaco,$conresaco,'ultcertid'))."','$this->ultcertid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["qv"]) || $this->qv != "")
             $resac = db_query("insert into db_acount values($acount,358,2273,'".AddSlashes(pg_result($resaco,$conresaco,'qv'))."','$this->qv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["inflat"]) || $this->inflat != "")
             $resac = db_query("insert into db_acount values($acount,358,507,'".AddSlashes(pg_result($resaco,$conresaco,'inflat'))."','$this->inflat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ultparcel"]) || $this->ultparcel != "")
             $resac = db_query("insert into db_acount values($acount,358,2274,'".AddSlashes(pg_result($resaco,$conresaco,'ultparcel'))."','$this->ultparcel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k00_hist"]) || $this->k00_hist != "")
             $resac = db_query("insert into db_acount values($acount,358,375,'".AddSlashes(pg_result($resaco,$conresaco,'k00_hist'))."','$this->k00_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_recparini"]) || $this->v04_recparini != "")
             $resac = db_query("insert into db_acount values($acount,358,2428,'".AddSlashes(pg_result($resaco,$conresaco,'v04_recparini'))."','$this->v04_recparini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_docum"]) || $this->v04_docum != "")
             $resac = db_query("insert into db_acount values($acount,358,5711,'".AddSlashes(pg_result($resaco,$conresaco,'v04_docum'))."','$this->v04_docum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_histpadparc"]) || $this->v04_histpadparc != "")
             $resac = db_query("insert into db_acount values($acount,358,8864,'".AddSlashes(pg_result($resaco,$conresaco,'v04_histpadparc'))."','$this->v04_histpadparc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_peticaoinicial"]) || $this->v04_peticaoinicial != "")
             $resac = db_query("insert into db_acount values($acount,358,9567,'".AddSlashes(pg_result($resaco,$conresaco,'v04_peticaoinicial'))."','$this->v04_peticaoinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_ordemendcda"]) || $this->v04_ordemendcda != "")
             $resac = db_query("insert into db_acount values($acount,358,9644,'".AddSlashes(pg_result($resaco,$conresaco,'v04_ordemendcda'))."','$this->v04_ordemendcda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_histjuros"]) || $this->v04_histjuros != "")
             $resac = db_query("insert into db_acount values($acount,358,9676,'".AddSlashes(pg_result($resaco,$conresaco,'v04_histjuros'))."','$this->v04_histjuros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_instit"]) || $this->v04_instit != "")
             $resac = db_query("insert into db_acount values($acount,358,10573,'".AddSlashes(pg_result($resaco,$conresaco,'v04_instit'))."','$this->v04_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_tipoinicial"]) || $this->v04_tipoinicial != "")
             $resac = db_query("insert into db_acount values($acount,358,10751,'".AddSlashes(pg_result($resaco,$conresaco,'v04_tipoinicial'))."','$this->v04_tipoinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_tipocertidao"]) || $this->v04_tipocertidao != "")
             $resac = db_query("insert into db_acount values($acount,358,10754,'".AddSlashes(pg_result($resaco,$conresaco,'v04_tipocertidao'))."','$this->v04_tipocertidao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_envolcdaiptu"]) || $this->v04_envolcdaiptu != "")
             $resac = db_query("insert into db_acount values($acount,358,11767,'".AddSlashes(pg_result($resaco,$conresaco,'v04_envolcdaiptu'))."','$this->v04_envolcdaiptu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_envolcdaiss"]) || $this->v04_envolcdaiss != "")
             $resac = db_query("insert into db_acount values($acount,358,11768,'".AddSlashes(pg_result($resaco,$conresaco,'v04_envolcdaiss'))."','$this->v04_envolcdaiss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_envolprinciptu"]) || $this->v04_envolprinciptu != "")
             $resac = db_query("insert into db_acount values($acount,358,11769,'".AddSlashes(pg_result($resaco,$conresaco,'v04_envolprinciptu'))."','$this->v04_envolprinciptu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_imphistcda"]) || $this->v04_imphistcda != "")
             $resac = db_query("insert into db_acount values($acount,358,11770,'".AddSlashes(pg_result($resaco,$conresaco,'v04_imphistcda'))."','$this->v04_imphistcda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_expfalecimentocda"]) || $this->v04_expfalecimentocda != "")
             $resac = db_query("insert into db_acount values($acount,358,14493,'".AddSlashes(pg_result($resaco,$conresaco,'v04_expfalecimentocda'))."','$this->v04_expfalecimentocda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_implivrofolha"]) || $this->v04_implivrofolha != "")
             $resac = db_query("insert into db_acount values($acount,358,14829,'".AddSlashes(pg_result($resaco,$conresaco,'v04_implivrofolha'))."','$this->v04_implivrofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_confexpfalec"]) || $this->v04_confexpfalec != "")
             $resac = db_query("insert into db_acount values($acount,358,15556,'".AddSlashes(pg_result($resaco,$conresaco,'v04_confexpfalec'))."','$this->v04_confexpfalec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_formgeracda"]) || $this->v04_formgeracda != "")
             $resac = db_query("insert into db_acount values($acount,358,17084,'".AddSlashes(pg_result($resaco,$conresaco,'v04_formgeracda'))."','$this->v04_formgeracda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v04_cobrarjurosmultacda"]) || $this->v04_cobrarjurosmultacda != "")
             $resac = db_query("insert into db_acount values($acount,358,21701,'".AddSlashes(pg_result($resaco,$conresaco,'v04_cobrarjurosmultacda'))."','$this->v04_cobrarjurosmultacda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "parametros da divida não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v04_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "parametros da divida não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v04_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v04_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($v04_instit=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($v04_instit));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,10573,'$v04_instit','E')");
           $resac  = db_query("insert into db_acount values($acount,358,2270,'','".AddSlashes(pg_result($resaco,$iresaco,'ultcoddiv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,486,'','".AddSlashes(pg_result($resaco,$iresaco,'codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,2271,'','".AddSlashes(pg_result($resaco,$iresaco,'codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,2272,'','".AddSlashes(pg_result($resaco,$iresaco,'ultcertid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,2273,'','".AddSlashes(pg_result($resaco,$iresaco,'qv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,507,'','".AddSlashes(pg_result($resaco,$iresaco,'inflat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,2274,'','".AddSlashes(pg_result($resaco,$iresaco,'ultparcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,375,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,2428,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_recparini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,5711,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_docum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,8864,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_histpadparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,9567,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_peticaoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,9644,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_ordemendcda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,9676,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_histjuros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,10573,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,10751,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_tipoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,10754,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_tipocertidao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,11767,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_envolcdaiptu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,11768,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_envolcdaiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,11769,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_envolprinciptu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,11770,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_imphistcda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,14493,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_expfalecimentocda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,14829,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_implivrofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,15556,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_confexpfalec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,17084,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_formgeracda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,358,21701,'','".AddSlashes(pg_result($resaco,$iresaco,'v04_cobrarjurosmultacda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pardiv
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($v04_instit)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " v04_instit = $v04_instit ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "parametros da divida não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v04_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "parametros da divida não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v04_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v04_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:pardiv";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($v04_instit = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from pardiv ";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = pardiv.k00_hist";
     $sql .= "      left  join inflan  on  inflan.i01_codigo = pardiv.inflat";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = pardiv.v04_tipoinicial and  arretipo.k00_tipo = pardiv.v04_tipocertidao";
     $sql .= "      inner join db_config  on  db_config.codigo = pardiv.v04_instit";
     $sql .= "      inner join db_documento  on  db_documento.db03_docum = pardiv.v04_docum";
     $sql .= "      inner join db_config  as a on   a.codigo = arretipo.k00_instit";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_config  as b on   b.codigo = db_documento.db03_instit";
     $sql .= "      inner join db_tipodoc  on  db_tipodoc.db08_codigo = db_documento.db03_tipodoc";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v04_instit)) {
         $sql2 .= " where pardiv.v04_instit = $v04_instit "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($v04_instit = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from pardiv ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v04_instit)){
         $sql2 .= " where pardiv.v04_instit = $v04_instit "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

   function sql_query_param ( $v04_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from pardiv ";
     $sql .= "      left  join histcalc        on  histcalc.k01_codigo    = pardiv.k00_hist        ";
     $sql .= "      left  join inflan          on  inflan.i01_codigo      = pardiv.inflat          ";
     $sql .= "      left  join arretipo        on  arretipo.k00_tipo      = pardiv.v04_tipoinicial "; 
     $sql .= "      left  join db_config       on  db_config.codigo       = pardiv.v04_instit      ";
     $sql .= "      left  join cadban          on  cadban.k15_codbco      = pardiv.codbco          ";
     $sql .= "                                and  cadban.k15_codage      = pardiv.codage          "; 
     $sql .= "      left  join cadtipo         on  cadtipo.k03_tipo       = arretipo.k03_tipo      ";
     $sql .= "      left  join cgm             on  cgm.z01_numcgm         = db_config.numcgm       ";
     $sql .= "      left  join cgm        as d on  d.z01_numcgm           = cadban.k15_numcgm      ";
     $sql .= "      left  join bancos          on  bancos.codbco          = cadban.k15_codbco      ";
     $sql .= "      left  join cgm        as f on  f.z01_numcgm           = cadban.k15_numcgm      ";
     $sql .= "      left  join db_documento    on  v04_docum              = db_documento.db03_docum";
     $sql .= "      left  join arretipo as tipoini  on v04_tipoinicial    = tipoini.k00_tipo";
     $sql .= "      left  join arretipo as tipocert on v04_tipocertidao   = tipocert.k00_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($v04_instit!=null ){
         $sql2 .= " where pardiv.v04_instit = $v04_instit "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
