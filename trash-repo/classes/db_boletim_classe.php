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
//CLASSE DA ENTIDADE boletim
class cl_boletim { 
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
   var $k11_data_dia = null; 
   var $k11_data_mes = null; 
   var $k11_data_ano = null; 
   var $k11_data = null; 
   var $k11_instit = 0; 
   var $k11_numbol = 0; 
   var $k11_libera = 'f'; 
   var $k11_lanca = 'f'; 
   var $k11_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k11_data = date = Data 
                 k11_instit = int4 = Instituição 
                 k11_numbol = int4 = Número Boletim 
                 k11_libera = bool = Libera Boletim 
                 k11_lanca = bool = Lancado Contabil 
                 k11_anousu = int4 = Ano 
                 ";
   //funcao construtor da classe 
   function cl_boletim() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("boletim"); 
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
       if($this->k11_data == ""){
         $this->k11_data_dia = ($this->k11_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_data_dia"]:$this->k11_data_dia);
         $this->k11_data_mes = ($this->k11_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_data_mes"]:$this->k11_data_mes);
         $this->k11_data_ano = ($this->k11_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_data_ano"]:$this->k11_data_ano);
         if($this->k11_data_dia != ""){
            $this->k11_data = $this->k11_data_ano."-".$this->k11_data_mes."-".$this->k11_data_dia;
         }
       }
       $this->k11_instit = ($this->k11_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_instit"]:$this->k11_instit);
       $this->k11_numbol = ($this->k11_numbol == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_numbol"]:$this->k11_numbol);
       $this->k11_libera = ($this->k11_libera == "f"?@$GLOBALS["HTTP_POST_VARS"]["k11_libera"]:$this->k11_libera);
       $this->k11_lanca = ($this->k11_lanca == "f"?@$GLOBALS["HTTP_POST_VARS"]["k11_lanca"]:$this->k11_lanca);
       $this->k11_anousu = ($this->k11_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_anousu"]:$this->k11_anousu);
     }else{
       $this->k11_data = ($this->k11_data == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_data_ano"]."-".@$GLOBALS["HTTP_POST_VARS"]["k11_data_mes"]."-".@$GLOBALS["HTTP_POST_VARS"]["k11_data_dia"]:$this->k11_data);
       $this->k11_instit = ($this->k11_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k11_instit"]:$this->k11_instit);
     }
   }
   // funcao para inclusao
   function incluir ($k11_data,$k11_instit){ 
      $this->atualizacampos();
     if($this->k11_numbol == null ){ 
       $this->erro_sql = " Campo Número Boletim nao Informado.";
       $this->erro_campo = "k11_numbol";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k11_libera == null ){ 
       $this->erro_sql = " Campo Libera Boletim nao Informado.";
       $this->erro_campo = "k11_libera";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k11_lanca == null ){ 
       $this->erro_sql = " Campo Lancado Contabil nao Informado.";
       $this->erro_campo = "k11_lanca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k11_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "k11_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k11_data = $k11_data; 
       $this->k11_instit = $k11_instit; 
     if(($this->k11_data == null) || ($this->k11_data == "") ){ 
       $this->erro_sql = " Campo k11_data nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k11_instit == null) || ($this->k11_instit == "") ){ 
       $this->erro_sql = " Campo k11_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into boletim(
                                       k11_data 
                                      ,k11_instit 
                                      ,k11_numbol 
                                      ,k11_libera 
                                      ,k11_lanca 
                                      ,k11_anousu 
                       )
                values (
                                ".($this->k11_data == "null" || $this->k11_data == ""?"null":"'".$this->k11_data."'")." 
                               ,$this->k11_instit 
                               ,$this->k11_numbol 
                               ,'$this->k11_libera' 
                               ,'$this->k11_lanca' 
                               ,$this->k11_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Boletim Caixa ($this->k11_data."-".$this->k11_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Boletim Caixa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Boletim Caixa ($this->k11_data."-".$this->k11_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k11_data."-".$this->k11_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k11_data,$this->k11_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1128,'$this->k11_data','I')");
       $resac = db_query("insert into db_acountkey values($acount,6165,'$this->k11_instit','I')");
       $resac = db_query("insert into db_acount values($acount,198,1128,'','".AddSlashes(pg_result($resaco,0,'k11_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,198,6165,'','".AddSlashes(pg_result($resaco,0,'k11_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,198,1127,'','".AddSlashes(pg_result($resaco,0,'k11_numbol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,198,1129,'','".AddSlashes(pg_result($resaco,0,'k11_libera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,198,1130,'','".AddSlashes(pg_result($resaco,0,'k11_lanca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,198,6166,'','".AddSlashes(pg_result($resaco,0,'k11_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k11_data=null,$k11_instit=null) { 
      $this->atualizacampos();
     $sql = " update boletim set ";
     $virgula = "";
     if(trim($this->k11_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k11_data_dia"] !="") ){ 
       $sql  .= $virgula." k11_data = '$this->k11_data' ";
       $virgula = ",";
       if(trim($this->k11_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k11_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k11_data_dia"])){ 
         $sql  .= $virgula." k11_data = null ";
         $virgula = ",";
         if(trim($this->k11_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k11_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k11_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_instit"])){ 
       $sql  .= $virgula." k11_instit = $this->k11_instit ";
       $virgula = ",";
       if(trim($this->k11_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "k11_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k11_numbol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_numbol"])){ 
       $sql  .= $virgula." k11_numbol = $this->k11_numbol ";
       $virgula = ",";
       if(trim($this->k11_numbol) == null ){ 
         $this->erro_sql = " Campo Número Boletim nao Informado.";
         $this->erro_campo = "k11_numbol";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k11_libera)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_libera"])){ 
       $sql  .= $virgula." k11_libera = '$this->k11_libera' ";
       $virgula = ",";
       if(trim($this->k11_libera) == null ){ 
         $this->erro_sql = " Campo Libera Boletim nao Informado.";
         $this->erro_campo = "k11_libera";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k11_lanca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_lanca"])){ 
       $sql  .= $virgula." k11_lanca = '$this->k11_lanca' ";
       $virgula = ",";
       if(trim($this->k11_lanca) == null ){ 
         $this->erro_sql = " Campo Lancado Contabil nao Informado.";
         $this->erro_campo = "k11_lanca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k11_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k11_anousu"])){ 
       $sql  .= $virgula." k11_anousu = $this->k11_anousu ";
       $virgula = ",";
       if(trim($this->k11_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "k11_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k11_data!=null){
       $sql .= " k11_data = '$this->k11_data'";
     }
     if($k11_instit!=null){
       $sql .= " and  k11_instit = $this->k11_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k11_data,$this->k11_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1128,'$this->k11_data','A')");
         $resac = db_query("insert into db_acountkey values($acount,6165,'$this->k11_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_data"]))
           $resac = db_query("insert into db_acount values($acount,198,1128,'".AddSlashes(pg_result($resaco,$conresaco,'k11_data'))."','$this->k11_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_instit"]))
           $resac = db_query("insert into db_acount values($acount,198,6165,'".AddSlashes(pg_result($resaco,$conresaco,'k11_instit'))."','$this->k11_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_numbol"]))
           $resac = db_query("insert into db_acount values($acount,198,1127,'".AddSlashes(pg_result($resaco,$conresaco,'k11_numbol'))."','$this->k11_numbol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_libera"]))
           $resac = db_query("insert into db_acount values($acount,198,1129,'".AddSlashes(pg_result($resaco,$conresaco,'k11_libera'))."','$this->k11_libera',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_lanca"]))
           $resac = db_query("insert into db_acount values($acount,198,1130,'".AddSlashes(pg_result($resaco,$conresaco,'k11_lanca'))."','$this->k11_lanca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k11_anousu"]))
           $resac = db_query("insert into db_acount values($acount,198,6166,'".AddSlashes(pg_result($resaco,$conresaco,'k11_anousu'))."','$this->k11_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Boletim Caixa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k11_data."-".$this->k11_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Boletim Caixa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k11_data."-".$this->k11_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k11_data."-".$this->k11_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k11_data=null,$k11_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k11_data,$k11_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1128,'$k11_data','E')");
         $resac = db_query("insert into db_acountkey values($acount,6165,'$k11_instit','E')");
         $resac = db_query("insert into db_acount values($acount,198,1128,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,198,6165,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,198,1127,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_numbol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,198,1129,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_libera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,198,1130,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_lanca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,198,6166,'','".AddSlashes(pg_result($resaco,$iresaco,'k11_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from boletim
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k11_data != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k11_data = '$k11_data' ";
        }
        if($k11_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k11_instit = $k11_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Boletim Caixa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k11_data."-".$k11_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Boletim Caixa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k11_data."-".$k11_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k11_data."-".$k11_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:boletim";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k11_data=null,$k11_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from boletim ";
     $sql .= "      inner join db_config  on  db_config.codigo = boletim.k11_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($k11_data!=null ){
         $sql2 .= " where boletim.k11_data = '$k11_data' "; 
       } 
       if($k11_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " boletim.k11_instit = $k11_instit "; 
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
   function sql_query_file ( $k11_data=null,$k11_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from boletim ";
     $sql2 = "";
     if($dbwhere==""){
       if($k11_data!=null ){
         $sql2 .= " where boletim.k11_data = '$k11_data' "; 
       } 
       if($k11_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " boletim.k11_instit = $k11_instit "; 
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