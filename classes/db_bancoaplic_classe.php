<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: caixa
//CLASSE DA ENTIDADE bancoaplic
class cl_bancoaplic { 
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
   var $k90_id = 0; 
   var $k90_conta = 0; 
   var $k90_data_dia = null; 
   var $k90_data_mes = null; 
   var $k90_data_ano = null; 
   var $k90_data = null; 
   var $k90_cpvlraplic = 0; 
   var $k90_cpvlrresg = 0; 
   var $k90_cpsldaplic = 0; 
   var $k90_pfsldaplicant = 0; 
   var $k90_pfvlraplic = 0; 
   var $k90_pfvlrresg = 0; 
   var $k90_pfsldaplicf = 0; 
   var $k90_vlrdisp = 0; 
   var $k90_sldtot = 0; 
   var $k90_codreceita = 0; 
   var $k90_vlrjuros = 0; 
   var $k90_cpsldaplicant = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k90_id = int8 = Código do Lançamento 
                 k90_conta = int8 = Conta reduzida do banco 
                 k90_data = date = Data do Lançamento 
                 k90_cpvlraplic = float8 = Valor Aplicato-cp 
                 k90_cpvlrresg = float8 = Valor Resgate-cp 
                 k90_cpsldaplic = float8 = Saldo Aplic.-cp 
                 k90_pfsldaplicant = float8 = Saldo Aplicado Ant.-pf 
                 k90_pfvlraplic = float8 = Valor Aplicado-pf 
                 k90_pfvlrresg = float8 = Valor Resgate-pf 
                 k90_pfsldaplicf = float8 = Saldo Final Aplicado-pf 
                 k90_vlrdisp = float8 = Valor Disponível 
                 k90_sldtot = float8 = Saldo Total 
                 k90_codreceita = int8 = Codigo receita 
                 k90_vlrjuros = float8 = Vlr.juros 
                 k90_cpsldaplicant = float8 = Saldo Aplicado Ant.-cp 
                 ";
   //funcao construtor da classe 
   function cl_bancoaplic() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bancoaplic"); 
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
       $this->k90_id = ($this->k90_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_id"]:$this->k90_id);
       $this->k90_conta = ($this->k90_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_conta"]:$this->k90_conta);
       if($this->k90_data == ""){
         $this->k90_data_dia = ($this->k90_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_data_dia"]:$this->k90_data_dia);
         $this->k90_data_mes = ($this->k90_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_data_mes"]:$this->k90_data_mes);
         $this->k90_data_ano = ($this->k90_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_data_ano"]:$this->k90_data_ano);
         if($this->k90_data_dia != ""){
            $this->k90_data = $this->k90_data_ano."-".$this->k90_data_mes."-".$this->k90_data_dia;
         }
       }
       $this->k90_cpvlraplic = ($this->k90_cpvlraplic == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_cpvlraplic"]:$this->k90_cpvlraplic);
       $this->k90_cpvlrresg = ($this->k90_cpvlrresg == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_cpvlrresg"]:$this->k90_cpvlrresg);
       $this->k90_cpsldaplic = ($this->k90_cpsldaplic == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_cpsldaplic"]:$this->k90_cpsldaplic);
       $this->k90_pfsldaplicant = ($this->k90_pfsldaplicant == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_pfsldaplicant"]:$this->k90_pfsldaplicant);
       $this->k90_pfvlraplic = ($this->k90_pfvlraplic == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_pfvlraplic"]:$this->k90_pfvlraplic);
       $this->k90_pfvlrresg = ($this->k90_pfvlrresg == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_pfvlrresg"]:$this->k90_pfvlrresg);
       $this->k90_pfsldaplicf = ($this->k90_pfsldaplicf == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_pfsldaplicf"]:$this->k90_pfsldaplicf);
       $this->k90_vlrdisp = ($this->k90_vlrdisp == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_vlrdisp"]:$this->k90_vlrdisp);
       $this->k90_sldtot = ($this->k90_sldtot == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_sldtot"]:$this->k90_sldtot);
       $this->k90_codreceita = ($this->k90_codreceita == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_codreceita"]:$this->k90_codreceita);
       $this->k90_vlrjuros = ($this->k90_vlrjuros == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_vlrjuros"]:$this->k90_vlrjuros);
       $this->k90_cpsldaplicant = ($this->k90_cpsldaplicant == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_cpsldaplicant"]:$this->k90_cpsldaplicant);
     }else{
       $this->k90_id = ($this->k90_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k90_id"]:$this->k90_id);
     }
   }
   // funcao para inclusao
   function incluir ($k90_id){ 
      $this->atualizacampos();
     if($this->k90_conta == null ){ 
       $this->erro_sql = " Campo Conta reduzida do banco nao Informado.";
       $this->erro_campo = "k90_conta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k90_data == null ){ 
       $this->erro_sql = " Campo Data do Lançamento nao Informado.";
       $this->erro_campo = "k90_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k90_cpvlraplic == null ){ 
       $this->k90_cpvlraplic = "0";
     }
     if($this->k90_cpvlrresg == null ){ 
       $this->k90_cpvlrresg = "0";
     }
     if($this->k90_cpsldaplic == null ){ 
       $this->k90_cpsldaplic = "0";
     }
     if($this->k90_pfsldaplicant == null ){ 
       $this->k90_pfsldaplicant = "0";
     }
     if($this->k90_pfvlraplic == null ){ 
       $this->k90_pfvlraplic = "0";
     }
     if($this->k90_pfvlrresg == null ){ 
       $this->k90_pfvlrresg = "0";
     }
     if($this->k90_pfsldaplicf == null ){ 
       $this->k90_pfsldaplicf = "0";
     }
     if($this->k90_vlrdisp == null ){ 
       $this->k90_vlrdisp = "0";
     }
     if($this->k90_sldtot == null ){ 
       $this->k90_sldtot = "0";
     }
     if($this->k90_codreceita == null ){ 
       $this->erro_sql = " Campo Codigo receita nao Informado.";
       $this->erro_campo = "k90_codreceita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k90_vlrjuros == null ){ 
       $this->k90_vlrjuros = "0";
     }
     if($this->k90_cpsldaplicant == null ){ 
       $this->k90_cpsldaplicant = "0";
     }
     if($k90_id == "" || $k90_id == null ){
       $result = db_query("select nextval('bancoaplic_k90_id_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bancoaplic_k90_id_seq do campo: k90_id"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k90_id = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from bancoaplic_k90_id_seq");
       if(($result != false) && (pg_result($result,0,0) < $k90_id)){
         $this->erro_sql = " Campo k90_id maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k90_id = $k90_id; 
       }
     }
     if(($this->k90_id == null) || ($this->k90_id == "") ){ 
       $this->erro_sql = " Campo k90_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bancoaplic(
                                       k90_id 
                                      ,k90_conta 
                                      ,k90_data 
                                      ,k90_cpvlraplic 
                                      ,k90_cpvlrresg 
                                      ,k90_cpsldaplic 
                                      ,k90_pfsldaplicant 
                                      ,k90_pfvlraplic 
                                      ,k90_pfvlrresg 
                                      ,k90_pfsldaplicf 
                                      ,k90_vlrdisp 
                                      ,k90_sldtot 
                                      ,k90_codreceita 
                                      ,k90_vlrjuros 
                                      ,k90_cpsldaplicant 
                       )
                values (
                                $this->k90_id 
                               ,$this->k90_conta 
                               ,".($this->k90_data == "null" || $this->k90_data == ""?"null":"'".$this->k90_data."'")." 
                               ,$this->k90_cpvlraplic 
                               ,$this->k90_cpvlrresg 
                               ,$this->k90_cpsldaplic 
                               ,$this->k90_pfsldaplicant 
                               ,$this->k90_pfvlraplic 
                               ,$this->k90_pfvlrresg 
                               ,$this->k90_pfsldaplicf 
                               ,$this->k90_vlrdisp 
                               ,$this->k90_sldtot 
                               ,$this->k90_codreceita 
                               ,$this->k90_vlrjuros 
                               ,$this->k90_cpsldaplicant 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Aplicações Bancárias ($this->k90_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Aplicações Bancárias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Aplicações Bancárias ($this->k90_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k90_id;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k90_id));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7219,'$this->k90_id','I')");
       $resac = db_query("insert into db_acount values($acount,1199,7219,'','".AddSlashes(pg_result($resaco,0,'k90_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1199,7220,'','".AddSlashes(pg_result($resaco,0,'k90_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1199,7221,'','".AddSlashes(pg_result($resaco,0,'k90_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1199,7222,'','".AddSlashes(pg_result($resaco,0,'k90_cpvlraplic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1199,7223,'','".AddSlashes(pg_result($resaco,0,'k90_cpvlrresg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1199,7224,'','".AddSlashes(pg_result($resaco,0,'k90_cpsldaplic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1199,7225,'','".AddSlashes(pg_result($resaco,0,'k90_pfsldaplicant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1199,7226,'','".AddSlashes(pg_result($resaco,0,'k90_pfvlraplic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1199,7227,'','".AddSlashes(pg_result($resaco,0,'k90_pfvlrresg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1199,7228,'','".AddSlashes(pg_result($resaco,0,'k90_pfsldaplicf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1199,7229,'','".AddSlashes(pg_result($resaco,0,'k90_vlrdisp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1199,7230,'','".AddSlashes(pg_result($resaco,0,'k90_sldtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1199,7231,'','".AddSlashes(pg_result($resaco,0,'k90_codreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1199,7237,'','".AddSlashes(pg_result($resaco,0,'k90_vlrjuros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1199,7236,'','".AddSlashes(pg_result($resaco,0,'k90_cpsldaplicant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k90_id=null) { 
      $this->atualizacampos();
     $sql = " update bancoaplic set ";
     $virgula = "";
     if(trim($this->k90_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_id"])){ 
       $sql  .= $virgula." k90_id = $this->k90_id ";
       $virgula = ",";
       if(trim($this->k90_id) == null ){ 
         $this->erro_sql = " Campo Código do Lançamento nao Informado.";
         $this->erro_campo = "k90_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k90_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_conta"])){ 
       $sql  .= $virgula." k90_conta = $this->k90_conta ";
       $virgula = ",";
       if(trim($this->k90_conta) == null ){ 
         $this->erro_sql = " Campo Conta reduzida do banco nao Informado.";
         $this->erro_campo = "k90_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k90_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k90_data_dia"] !="") ){ 
       $sql  .= $virgula." k90_data = '$this->k90_data' ";
       $virgula = ",";
       if(trim($this->k90_data) == null ){ 
         $this->erro_sql = " Campo Data do Lançamento nao Informado.";
         $this->erro_campo = "k90_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k90_data_dia"])){ 
         $sql  .= $virgula." k90_data = null ";
         $virgula = ",";
         if(trim($this->k90_data) == null ){ 
           $this->erro_sql = " Campo Data do Lançamento nao Informado.";
           $this->erro_campo = "k90_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k90_cpvlraplic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_cpvlraplic"])){ 
        if(trim($this->k90_cpvlraplic)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k90_cpvlraplic"])){ 
           $this->k90_cpvlraplic = "0" ; 
        } 
       $sql  .= $virgula." k90_cpvlraplic = $this->k90_cpvlraplic ";
       $virgula = ",";
     }
     if(trim($this->k90_cpvlrresg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_cpvlrresg"])){ 
        if(trim($this->k90_cpvlrresg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k90_cpvlrresg"])){ 
           $this->k90_cpvlrresg = "0" ; 
        } 
       $sql  .= $virgula." k90_cpvlrresg = $this->k90_cpvlrresg ";
       $virgula = ",";
     }
     if(trim($this->k90_cpsldaplic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_cpsldaplic"])){ 
        if(trim($this->k90_cpsldaplic)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k90_cpsldaplic"])){ 
           $this->k90_cpsldaplic = "0" ; 
        } 
       $sql  .= $virgula." k90_cpsldaplic = $this->k90_cpsldaplic ";
       $virgula = ",";
     }
     if(trim($this->k90_pfsldaplicant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_pfsldaplicant"])){ 
        if(trim($this->k90_pfsldaplicant)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k90_pfsldaplicant"])){ 
           $this->k90_pfsldaplicant = "0" ; 
        } 
       $sql  .= $virgula." k90_pfsldaplicant = $this->k90_pfsldaplicant ";
       $virgula = ",";
     }
     if(trim($this->k90_pfvlraplic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_pfvlraplic"])){ 
        if(trim($this->k90_pfvlraplic)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k90_pfvlraplic"])){ 
           $this->k90_pfvlraplic = "0" ; 
        } 
       $sql  .= $virgula." k90_pfvlraplic = $this->k90_pfvlraplic ";
       $virgula = ",";
     }
     if(trim($this->k90_pfvlrresg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_pfvlrresg"])){ 
        if(trim($this->k90_pfvlrresg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k90_pfvlrresg"])){ 
           $this->k90_pfvlrresg = "0" ; 
        } 
       $sql  .= $virgula." k90_pfvlrresg = $this->k90_pfvlrresg ";
       $virgula = ",";
     }
     if(trim($this->k90_pfsldaplicf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_pfsldaplicf"])){ 
        if(trim($this->k90_pfsldaplicf)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k90_pfsldaplicf"])){ 
           $this->k90_pfsldaplicf = "0" ; 
        } 
       $sql  .= $virgula." k90_pfsldaplicf = $this->k90_pfsldaplicf ";
       $virgula = ",";
     }
     if(trim($this->k90_vlrdisp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_vlrdisp"])){ 
        if(trim($this->k90_vlrdisp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k90_vlrdisp"])){ 
           $this->k90_vlrdisp = "0" ; 
        } 
       $sql  .= $virgula." k90_vlrdisp = $this->k90_vlrdisp ";
       $virgula = ",";
     }
     if(trim($this->k90_sldtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_sldtot"])){ 
        if(trim($this->k90_sldtot)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k90_sldtot"])){ 
           $this->k90_sldtot = "0" ; 
        } 
       $sql  .= $virgula." k90_sldtot = $this->k90_sldtot ";
       $virgula = ",";
     }
     if(trim($this->k90_codreceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_codreceita"])){ 
       $sql  .= $virgula." k90_codreceita = $this->k90_codreceita ";
       $virgula = ",";
       if(trim($this->k90_codreceita) == null ){ 
         $this->erro_sql = " Campo Codigo receita nao Informado.";
         $this->erro_campo = "k90_codreceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k90_vlrjuros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_vlrjuros"])){ 
        if(trim($this->k90_vlrjuros)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k90_vlrjuros"])){ 
           $this->k90_vlrjuros = "0" ; 
        } 
       $sql  .= $virgula." k90_vlrjuros = $this->k90_vlrjuros ";
       $virgula = ",";
     }
     if(trim($this->k90_cpsldaplicant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k90_cpsldaplicant"])){ 
        if(trim($this->k90_cpsldaplicant)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k90_cpsldaplicant"])){ 
           $this->k90_cpsldaplicant = "0" ; 
        } 
       $sql  .= $virgula." k90_cpsldaplicant = $this->k90_cpsldaplicant ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k90_id!=null){
       $sql .= " k90_id = $this->k90_id";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k90_id));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7219,'$this->k90_id','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_id"]))
           $resac = db_query("insert into db_acount values($acount,1199,7219,'".AddSlashes(pg_result($resaco,$conresaco,'k90_id'))."','$this->k90_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_conta"]))
           $resac = db_query("insert into db_acount values($acount,1199,7220,'".AddSlashes(pg_result($resaco,$conresaco,'k90_conta'))."','$this->k90_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_data"]))
           $resac = db_query("insert into db_acount values($acount,1199,7221,'".AddSlashes(pg_result($resaco,$conresaco,'k90_data'))."','$this->k90_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_cpvlraplic"]))
           $resac = db_query("insert into db_acount values($acount,1199,7222,'".AddSlashes(pg_result($resaco,$conresaco,'k90_cpvlraplic'))."','$this->k90_cpvlraplic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_cpvlrresg"]))
           $resac = db_query("insert into db_acount values($acount,1199,7223,'".AddSlashes(pg_result($resaco,$conresaco,'k90_cpvlrresg'))."','$this->k90_cpvlrresg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_cpsldaplic"]))
           $resac = db_query("insert into db_acount values($acount,1199,7224,'".AddSlashes(pg_result($resaco,$conresaco,'k90_cpsldaplic'))."','$this->k90_cpsldaplic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_pfsldaplicant"]))
           $resac = db_query("insert into db_acount values($acount,1199,7225,'".AddSlashes(pg_result($resaco,$conresaco,'k90_pfsldaplicant'))."','$this->k90_pfsldaplicant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_pfvlraplic"]))
           $resac = db_query("insert into db_acount values($acount,1199,7226,'".AddSlashes(pg_result($resaco,$conresaco,'k90_pfvlraplic'))."','$this->k90_pfvlraplic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_pfvlrresg"]))
           $resac = db_query("insert into db_acount values($acount,1199,7227,'".AddSlashes(pg_result($resaco,$conresaco,'k90_pfvlrresg'))."','$this->k90_pfvlrresg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_pfsldaplicf"]))
           $resac = db_query("insert into db_acount values($acount,1199,7228,'".AddSlashes(pg_result($resaco,$conresaco,'k90_pfsldaplicf'))."','$this->k90_pfsldaplicf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_vlrdisp"]))
           $resac = db_query("insert into db_acount values($acount,1199,7229,'".AddSlashes(pg_result($resaco,$conresaco,'k90_vlrdisp'))."','$this->k90_vlrdisp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_sldtot"]))
           $resac = db_query("insert into db_acount values($acount,1199,7230,'".AddSlashes(pg_result($resaco,$conresaco,'k90_sldtot'))."','$this->k90_sldtot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_codreceita"]))
           $resac = db_query("insert into db_acount values($acount,1199,7231,'".AddSlashes(pg_result($resaco,$conresaco,'k90_codreceita'))."','$this->k90_codreceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_vlrjuros"]))
           $resac = db_query("insert into db_acount values($acount,1199,7237,'".AddSlashes(pg_result($resaco,$conresaco,'k90_vlrjuros'))."','$this->k90_vlrjuros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k90_cpsldaplicant"]))
           $resac = db_query("insert into db_acount values($acount,1199,7236,'".AddSlashes(pg_result($resaco,$conresaco,'k90_cpsldaplicant'))."','$this->k90_cpsldaplicant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Aplicações Bancárias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k90_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Aplicações Bancárias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k90_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k90_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k90_id=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k90_id));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7219,'$k90_id','E')");
         $resac = db_query("insert into db_acount values($acount,1199,7219,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1199,7220,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1199,7221,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1199,7222,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_cpvlraplic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1199,7223,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_cpvlrresg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1199,7224,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_cpsldaplic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1199,7225,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_pfsldaplicant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1199,7226,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_pfvlraplic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1199,7227,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_pfvlrresg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1199,7228,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_pfsldaplicf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1199,7229,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_vlrdisp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1199,7230,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_sldtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1199,7231,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_codreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1199,7237,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_vlrjuros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1199,7236,'','".AddSlashes(pg_result($resaco,$iresaco,'k90_cpsldaplicant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bancoaplic
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k90_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k90_id = $k90_id ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Aplicações Bancárias nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k90_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Aplicações Bancárias nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k90_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k90_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:bancoaplic";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k90_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bancoaplic ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = bancoaplic.k90_codreceita";
     $sql .= "      inner join saltes  on  saltes.k13_conta = bancoaplic.k90_conta";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql2 = "";
     if($dbwhere==""){
       if($k90_id!=null ){
         $sql2 .= " where bancoaplic.k90_id = $k90_id "; 
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
   function sql_query_file ( $k90_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bancoaplic ";
     $sql2 = "";
     if($dbwhere==""){
       if($k90_id!=null ){
         $sql2 .= " where bancoaplic.k90_id = $k90_id "; 
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
?>