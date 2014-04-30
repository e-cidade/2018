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

//MODULO: compras
//CLASSE DA ENTIDADE pcfornereprlegal
class cl_pcfornereprlegal { 
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
   var $pc81_sequencia = 0; 
   var $pc81_cgmforn = 0; 
   var $pc81_cgmresp = 0; 
   var $pc81_datini_dia = null; 
   var $pc81_datini_mes = null; 
   var $pc81_datini_ano = null; 
   var $pc81_datini = null; 
   var $pc81_datfin_dia = null; 
   var $pc81_datfin_mes = null; 
   var $pc81_datfin_ano = null; 
   var $pc81_datfin = null; 
   var $pc81_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc81_sequencia = int4 = Sequencial do Representante 
                 pc81_cgmforn = int4 = CGM do Fornecedor 
                 pc81_cgmresp = int4 = CGM do Representante 
                 pc81_datini = date = Data Inicial 
                 pc81_datfin = date = Data Final 
                 pc81_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_pcfornereprlegal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcfornereprlegal"); 
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
       $this->pc81_sequencia = ($this->pc81_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_sequencia"]:$this->pc81_sequencia);
       $this->pc81_cgmforn = ($this->pc81_cgmforn == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_cgmforn"]:$this->pc81_cgmforn);
       $this->pc81_cgmresp = ($this->pc81_cgmresp == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_cgmresp"]:$this->pc81_cgmresp);
       if($this->pc81_datini == ""){
         $this->pc81_datini_dia = ($this->pc81_datini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_datini_dia"]:$this->pc81_datini_dia);
         $this->pc81_datini_mes = ($this->pc81_datini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_datini_mes"]:$this->pc81_datini_mes);
         $this->pc81_datini_ano = ($this->pc81_datini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_datini_ano"]:$this->pc81_datini_ano);
         if($this->pc81_datini_dia != ""){
            $this->pc81_datini = $this->pc81_datini_ano."-".$this->pc81_datini_mes."-".$this->pc81_datini_dia;
         }
       }
       if($this->pc81_datfin == ""){
         $this->pc81_datfin_dia = ($this->pc81_datfin_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_datfin_dia"]:$this->pc81_datfin_dia);
         $this->pc81_datfin_mes = ($this->pc81_datfin_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_datfin_mes"]:$this->pc81_datfin_mes);
         $this->pc81_datfin_ano = ($this->pc81_datfin_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_datfin_ano"]:$this->pc81_datfin_ano);
         if($this->pc81_datfin_dia != ""){
            $this->pc81_datfin = $this->pc81_datfin_ano."-".$this->pc81_datfin_mes."-".$this->pc81_datfin_dia;
         }
       }
       $this->pc81_obs = ($this->pc81_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_obs"]:$this->pc81_obs);
     }else{
       $this->pc81_sequencia = ($this->pc81_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc81_sequencia"]:$this->pc81_sequencia);
     }
   }
   // funcao para inclusao
   function incluir ($pc81_sequencia){ 
      $this->atualizacampos();
     if($this->pc81_cgmforn == null ){ 
       $this->erro_sql = " Campo CGM do Fornecedor nao Informado.";
       $this->erro_campo = "pc81_cgmforn";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc81_cgmresp == null ){ 
       $this->erro_sql = " Campo CGM do Representante nao Informado.";
       $this->erro_campo = "pc81_cgmresp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc81_datini == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "pc81_datini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc81_datfin == null ){ 
       $this->pc81_datfin = "null";
     }
     if($pc81_sequencia == "" || $pc81_sequencia == null ){
       $result = db_query("select nextval('pcfornereprlegal_pc81_sequencia_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcfornereprlegal_pc81_sequencia_seq do campo: pc81_sequencia"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc81_sequencia = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcfornereprlegal_pc81_sequencia_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc81_sequencia)){
         $this->erro_sql = " Campo pc81_sequencia maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc81_sequencia = $pc81_sequencia; 
       }
     }
     if(($this->pc81_sequencia == null) || ($this->pc81_sequencia == "") ){ 
       $this->erro_sql = " Campo pc81_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcfornereprlegal(
                                       pc81_sequencia 
                                      ,pc81_cgmforn 
                                      ,pc81_cgmresp 
                                      ,pc81_datini 
                                      ,pc81_datfin 
                                      ,pc81_obs 
                       )
                values (
                                $this->pc81_sequencia 
                               ,$this->pc81_cgmforn 
                               ,$this->pc81_cgmresp 
                               ,".($this->pc81_datini == "null" || $this->pc81_datini == ""?"null":"'".$this->pc81_datini."'")." 
                               ,".($this->pc81_datfin == "null" || $this->pc81_datfin == ""?"null":"'".$this->pc81_datfin."'")." 
                               ,'$this->pc81_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Representantes Legais do Cliente ($this->pc81_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Representantes Legais do Cliente já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Representantes Legais do Cliente ($this->pc81_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc81_sequencia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc81_sequencia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9617,'$this->pc81_sequencia','I')");
       $resac = db_query("insert into db_acount values($acount,1655,9617,'','".AddSlashes(pg_result($resaco,0,'pc81_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1655,9618,'','".AddSlashes(pg_result($resaco,0,'pc81_cgmforn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1655,9619,'','".AddSlashes(pg_result($resaco,0,'pc81_cgmresp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1655,9620,'','".AddSlashes(pg_result($resaco,0,'pc81_datini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1655,9621,'','".AddSlashes(pg_result($resaco,0,'pc81_datfin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1655,9622,'','".AddSlashes(pg_result($resaco,0,'pc81_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc81_sequencia=null) { 
      $this->atualizacampos();
     $sql = " update pcfornereprlegal set ";
     $virgula = "";
     if(trim($this->pc81_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc81_sequencia"])){ 
       $sql  .= $virgula." pc81_sequencia = $this->pc81_sequencia ";
       $virgula = ",";
       if(trim($this->pc81_sequencia) == null ){ 
         $this->erro_sql = " Campo Sequencial do Representante nao Informado.";
         $this->erro_campo = "pc81_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc81_cgmforn)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc81_cgmforn"])){ 
       $sql  .= $virgula." pc81_cgmforn = $this->pc81_cgmforn ";
       $virgula = ",";
       if(trim($this->pc81_cgmforn) == null ){ 
         $this->erro_sql = " Campo CGM do Fornecedor nao Informado.";
         $this->erro_campo = "pc81_cgmforn";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc81_cgmresp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc81_cgmresp"])){ 
       $sql  .= $virgula." pc81_cgmresp = $this->pc81_cgmresp ";
       $virgula = ",";
       if(trim($this->pc81_cgmresp) == null ){ 
         $this->erro_sql = " Campo CGM do Representante nao Informado.";
         $this->erro_campo = "pc81_cgmresp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc81_datini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc81_datini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc81_datini_dia"] !="") ){ 
       $sql  .= $virgula." pc81_datini = '$this->pc81_datini' ";
       $virgula = ",";
       if(trim($this->pc81_datini) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "pc81_datini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc81_datini_dia"])){ 
         $sql  .= $virgula." pc81_datini = null ";
         $virgula = ",";
         if(trim($this->pc81_datini) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "pc81_datini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc81_datfin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc81_datfin_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc81_datfin_dia"] !="") ){ 
       $sql  .= $virgula." pc81_datfin = '$this->pc81_datfin' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc81_datfin_dia"])){ 
         $sql  .= $virgula." pc81_datfin = null ";
         $virgula = ",";
       }
     }
     if(trim($this->pc81_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc81_obs"])){ 
       $sql  .= $virgula." pc81_obs = '$this->pc81_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($pc81_sequencia!=null){
       $sql .= " pc81_sequencia = $this->pc81_sequencia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc81_sequencia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9617,'$this->pc81_sequencia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc81_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1655,9617,'".AddSlashes(pg_result($resaco,$conresaco,'pc81_sequencia'))."','$this->pc81_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc81_cgmforn"]))
           $resac = db_query("insert into db_acount values($acount,1655,9618,'".AddSlashes(pg_result($resaco,$conresaco,'pc81_cgmforn'))."','$this->pc81_cgmforn',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc81_cgmresp"]))
           $resac = db_query("insert into db_acount values($acount,1655,9619,'".AddSlashes(pg_result($resaco,$conresaco,'pc81_cgmresp'))."','$this->pc81_cgmresp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc81_datini"]))
           $resac = db_query("insert into db_acount values($acount,1655,9620,'".AddSlashes(pg_result($resaco,$conresaco,'pc81_datini'))."','$this->pc81_datini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc81_datfin"]))
           $resac = db_query("insert into db_acount values($acount,1655,9621,'".AddSlashes(pg_result($resaco,$conresaco,'pc81_datfin'))."','$this->pc81_datfin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc81_obs"]))
           $resac = db_query("insert into db_acount values($acount,1655,9622,'".AddSlashes(pg_result($resaco,$conresaco,'pc81_obs'))."','$this->pc81_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Representantes Legais do Cliente nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc81_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Representantes Legais do Cliente nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc81_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc81_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc81_sequencia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc81_sequencia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9617,'$pc81_sequencia','E')");
         $resac = db_query("insert into db_acount values($acount,1655,9617,'','".AddSlashes(pg_result($resaco,$iresaco,'pc81_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1655,9618,'','".AddSlashes(pg_result($resaco,$iresaco,'pc81_cgmforn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1655,9619,'','".AddSlashes(pg_result($resaco,$iresaco,'pc81_cgmresp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1655,9620,'','".AddSlashes(pg_result($resaco,$iresaco,'pc81_datini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1655,9621,'','".AddSlashes(pg_result($resaco,$iresaco,'pc81_datfin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1655,9622,'','".AddSlashes(pg_result($resaco,$iresaco,'pc81_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcfornereprlegal
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc81_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc81_sequencia = $pc81_sequencia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Representantes Legais do Cliente nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc81_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Representantes Legais do Cliente nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc81_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc81_sequencia;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcfornereprlegal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc81_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornereprlegal ";
     $sql .= "      inner join cgm a on  a.z01_numcgm = pcfornereprlegal.pc81_cgmforn";
     $sql .= "      inner join cgm b on  b.z01_numcgm = pcfornereprlegal.pc81_cgmresp";
     $sql2 = "";
     if($dbwhere==""){
       if($pc81_sequencia!=null ){
         $sql2 .= " where pcfornereprlegal.pc81_sequencia = $pc81_sequencia "; 
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
   function sql_query_file ( $pc81_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornereprlegal ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc81_sequencia!=null ){
         $sql2 .= " where pcfornereprlegal.pc81_sequencia = $pc81_sequencia "; 
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