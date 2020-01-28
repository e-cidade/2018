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
//CLASSE DA ENTIDADE disbancotxt
class cl_disbancotxt { 
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
   var $k34_sequencial = 0; 
   var $k34_numpremigra = null; 
   var $k34_valor = 0; 
   var $k34_dtvenc_dia = null; 
   var $k34_dtvenc_mes = null; 
   var $k34_dtvenc_ano = null; 
   var $k34_dtvenc = null; 
   var $k34_dtpago_dia = null; 
   var $k34_dtpago_mes = null; 
   var $k34_dtpago_ano = null; 
   var $k34_dtpago = null; 
   var $k34_codret = 0; 
   var $k34_diferenca = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k34_sequencial = int4 = Sequencial 
                 k34_numpremigra = varchar(50) = Numpre do txt 
                 k34_valor = float8 = Valor 
                 k34_dtvenc = date = Vencimento 
                 k34_dtpago = date = Data do pagamento 
                 k34_codret = int4 = Codret 
                 k34_diferenca = float8 = Diferenca 
                 ";
   //funcao construtor da classe 
   function cl_disbancotxt() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("disbancotxt"); 
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
       $this->k34_sequencial = ($this->k34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k34_sequencial"]:$this->k34_sequencial);
       $this->k34_numpremigra = ($this->k34_numpremigra == ""?@$GLOBALS["HTTP_POST_VARS"]["k34_numpremigra"]:$this->k34_numpremigra);
       $this->k34_valor = ($this->k34_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k34_valor"]:$this->k34_valor);
       if($this->k34_dtvenc == ""){
         $this->k34_dtvenc_dia = ($this->k34_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k34_dtvenc_dia"]:$this->k34_dtvenc_dia);
         $this->k34_dtvenc_mes = ($this->k34_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k34_dtvenc_mes"]:$this->k34_dtvenc_mes);
         $this->k34_dtvenc_ano = ($this->k34_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k34_dtvenc_ano"]:$this->k34_dtvenc_ano);
         if($this->k34_dtvenc_dia != ""){
            $this->k34_dtvenc = $this->k34_dtvenc_ano."-".$this->k34_dtvenc_mes."-".$this->k34_dtvenc_dia;
         }
       }
       if($this->k34_dtpago == ""){
         $this->k34_dtpago_dia = ($this->k34_dtpago_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k34_dtpago_dia"]:$this->k34_dtpago_dia);
         $this->k34_dtpago_mes = ($this->k34_dtpago_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k34_dtpago_mes"]:$this->k34_dtpago_mes);
         $this->k34_dtpago_ano = ($this->k34_dtpago_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k34_dtpago_ano"]:$this->k34_dtpago_ano);
         if($this->k34_dtpago_dia != ""){
            $this->k34_dtpago = $this->k34_dtpago_ano."-".$this->k34_dtpago_mes."-".$this->k34_dtpago_dia;
         }
       }
       $this->k34_codret = ($this->k34_codret == ""?@$GLOBALS["HTTP_POST_VARS"]["k34_codret"]:$this->k34_codret);
       $this->k34_diferenca = ($this->k34_diferenca == ""?@$GLOBALS["HTTP_POST_VARS"]["k34_diferenca"]:$this->k34_diferenca);
     }else{
       $this->k34_sequencial = ($this->k34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k34_sequencial"]:$this->k34_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k34_sequencial){ 
      $this->atualizacampos();
     if($this->k34_numpremigra == null ){ 
       $this->erro_sql = " Campo Numpre do txt nao Informado.";
       $this->erro_campo = "k34_numpremigra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k34_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "k34_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k34_dtvenc == null ){ 
       $this->erro_sql = " Campo Vencimento nao Informado.";
       $this->erro_campo = "k34_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k34_dtpago == null ){ 
       $this->erro_sql = " Campo Data do pagamento nao Informado.";
       $this->erro_campo = "k34_dtpago_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k34_codret == null ){ 
       $this->erro_sql = " Campo Codret nao Informado.";
       $this->erro_campo = "k34_codret";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k34_diferenca == null ){ 
       $this->erro_sql = " Campo Diferenca nao Informado.";
       $this->erro_campo = "k34_diferenca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k34_sequencial == "" || $k34_sequencial == null ){
       $result = db_query("select nextval('disbancotxt_k34_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: disbancotxt_k34_sequencial_seq do campo: k34_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k34_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from disbancotxt_k34_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k34_sequencial)){
         $this->erro_sql = " Campo k34_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k34_sequencial = $k34_sequencial; 
       }
     }
     if(($this->k34_sequencial == null) || ($this->k34_sequencial == "") ){ 
       $this->erro_sql = " Campo k34_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into disbancotxt(
                                       k34_sequencial 
                                      ,k34_numpremigra 
                                      ,k34_valor 
                                      ,k34_dtvenc 
                                      ,k34_dtpago 
                                      ,k34_codret 
                                      ,k34_diferenca 
                       )
                values (
                                $this->k34_sequencial 
                               ,'$this->k34_numpremigra' 
                               ,$this->k34_valor 
                               ,".($this->k34_dtvenc == "null" || $this->k34_dtvenc == ""?"null":"'".$this->k34_dtvenc."'")." 
                               ,".($this->k34_dtpago == "null" || $this->k34_dtpago == ""?"null":"'".$this->k34_dtpago."'")." 
                               ,$this->k34_codret 
                               ,$this->k34_diferenca 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registros do txt ($this->k34_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registros do txt já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registros do txt ($this->k34_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k34_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k34_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8682,'$this->k34_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1481,8682,'','".AddSlashes(pg_result($resaco,0,'k34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1481,8683,'','".AddSlashes(pg_result($resaco,0,'k34_numpremigra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1481,8684,'','".AddSlashes(pg_result($resaco,0,'k34_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1481,8685,'','".AddSlashes(pg_result($resaco,0,'k34_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1481,8686,'','".AddSlashes(pg_result($resaco,0,'k34_dtpago'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1481,8697,'','".AddSlashes(pg_result($resaco,0,'k34_codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1481,8698,'','".AddSlashes(pg_result($resaco,0,'k34_diferenca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k34_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update disbancotxt set ";
     $virgula = "";
     if(trim($this->k34_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k34_sequencial"])){ 
       $sql  .= $virgula." k34_sequencial = $this->k34_sequencial ";
       $virgula = ",";
       if(trim($this->k34_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k34_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k34_numpremigra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k34_numpremigra"])){ 
       $sql  .= $virgula." k34_numpremigra = '$this->k34_numpremigra' ";
       $virgula = ",";
       if(trim($this->k34_numpremigra) == null ){ 
         $this->erro_sql = " Campo Numpre do txt nao Informado.";
         $this->erro_campo = "k34_numpremigra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k34_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k34_valor"])){ 
       $sql  .= $virgula." k34_valor = $this->k34_valor ";
       $virgula = ",";
       if(trim($this->k34_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "k34_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k34_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k34_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k34_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." k34_dtvenc = '$this->k34_dtvenc' ";
       $virgula = ",";
       if(trim($this->k34_dtvenc) == null ){ 
         $this->erro_sql = " Campo Vencimento nao Informado.";
         $this->erro_campo = "k34_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k34_dtvenc_dia"])){ 
         $sql  .= $virgula." k34_dtvenc = null ";
         $virgula = ",";
         if(trim($this->k34_dtvenc) == null ){ 
           $this->erro_sql = " Campo Vencimento nao Informado.";
           $this->erro_campo = "k34_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k34_dtpago)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k34_dtpago_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k34_dtpago_dia"] !="") ){ 
       $sql  .= $virgula." k34_dtpago = '$this->k34_dtpago' ";
       $virgula = ",";
       if(trim($this->k34_dtpago) == null ){ 
         $this->erro_sql = " Campo Data do pagamento nao Informado.";
         $this->erro_campo = "k34_dtpago_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k34_dtpago_dia"])){ 
         $sql  .= $virgula." k34_dtpago = null ";
         $virgula = ",";
         if(trim($this->k34_dtpago) == null ){ 
           $this->erro_sql = " Campo Data do pagamento nao Informado.";
           $this->erro_campo = "k34_dtpago_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k34_codret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k34_codret"])){ 
       $sql  .= $virgula." k34_codret = $this->k34_codret ";
       $virgula = ",";
       if(trim($this->k34_codret) == null ){ 
         $this->erro_sql = " Campo Codret nao Informado.";
         $this->erro_campo = "k34_codret";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k34_diferenca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k34_diferenca"])){ 
       $sql  .= $virgula." k34_diferenca = $this->k34_diferenca ";
       $virgula = ",";
       if(trim($this->k34_diferenca) == null ){ 
         $this->erro_sql = " Campo Diferenca nao Informado.";
         $this->erro_campo = "k34_diferenca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k34_sequencial!=null){
       $sql .= " k34_sequencial = $this->k34_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k34_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8682,'$this->k34_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k34_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1481,8682,'".AddSlashes(pg_result($resaco,$conresaco,'k34_sequencial'))."','$this->k34_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k34_numpremigra"]))
           $resac = db_query("insert into db_acount values($acount,1481,8683,'".AddSlashes(pg_result($resaco,$conresaco,'k34_numpremigra'))."','$this->k34_numpremigra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k34_valor"]))
           $resac = db_query("insert into db_acount values($acount,1481,8684,'".AddSlashes(pg_result($resaco,$conresaco,'k34_valor'))."','$this->k34_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k34_dtvenc"]))
           $resac = db_query("insert into db_acount values($acount,1481,8685,'".AddSlashes(pg_result($resaco,$conresaco,'k34_dtvenc'))."','$this->k34_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k34_dtpago"]))
           $resac = db_query("insert into db_acount values($acount,1481,8686,'".AddSlashes(pg_result($resaco,$conresaco,'k34_dtpago'))."','$this->k34_dtpago',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k34_codret"]))
           $resac = db_query("insert into db_acount values($acount,1481,8697,'".AddSlashes(pg_result($resaco,$conresaco,'k34_codret'))."','$this->k34_codret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k34_diferenca"]))
           $resac = db_query("insert into db_acount values($acount,1481,8698,'".AddSlashes(pg_result($resaco,$conresaco,'k34_diferenca'))."','$this->k34_diferenca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros do txt nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k34_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registros do txt nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k34_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k34_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8682,'$k34_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1481,8682,'','".AddSlashes(pg_result($resaco,$iresaco,'k34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1481,8683,'','".AddSlashes(pg_result($resaco,$iresaco,'k34_numpremigra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1481,8684,'','".AddSlashes(pg_result($resaco,$iresaco,'k34_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1481,8685,'','".AddSlashes(pg_result($resaco,$iresaco,'k34_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1481,8686,'','".AddSlashes(pg_result($resaco,$iresaco,'k34_dtpago'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1481,8697,'','".AddSlashes(pg_result($resaco,$iresaco,'k34_codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1481,8698,'','".AddSlashes(pg_result($resaco,$iresaco,'k34_diferenca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from disbancotxt
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k34_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k34_sequencial = $k34_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros do txt nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k34_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registros do txt nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k34_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:disbancotxt";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from disbancotxt ";
     $sql2 = "";
     if($dbwhere==""){
       if($k34_sequencial!=null ){
         $sql2 .= " where disbancotxt.k34_sequencial = $k34_sequencial "; 
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
   function sql_query_file ( $k34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from disbancotxt ";
     $sql2 = "";
     if($dbwhere==""){
       if($k34_sequencial!=null ){
         $sql2 .= " where disbancotxt.k34_sequencial = $k34_sequencial "; 
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