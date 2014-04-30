<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: empenho
//CLASSE DA ENTIDADE empagegera
class cl_empagegera { 
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
   var $e87_codgera = 0; 
   var $e87_descgera = null; 
   var $e87_data_dia = null; 
   var $e87_data_mes = null; 
   var $e87_data_ano = null; 
   var $e87_data = null; 
   var $e87_hora = null; 
   var $e87_dataproc_dia = null; 
   var $e87_dataproc_mes = null; 
   var $e87_dataproc_ano = null; 
   var $e87_dataproc = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e87_codgera = int4 = Código 
                 e87_descgera = varchar(40) = Descrição 
                 e87_data = date = Data 
                 e87_hora = varchar(5) = Hora 
                 e87_dataproc = date = Autoriza pgto. 
                 ";
   //funcao construtor da classe 
   function cl_empagegera() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empagegera"); 
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
       $this->e87_codgera = ($this->e87_codgera == ""?@$GLOBALS["HTTP_POST_VARS"]["e87_codgera"]:$this->e87_codgera);
       $this->e87_descgera = ($this->e87_descgera == ""?@$GLOBALS["HTTP_POST_VARS"]["e87_descgera"]:$this->e87_descgera);
       if($this->e87_data == ""){
         $this->e87_data_dia = ($this->e87_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e87_data_dia"]:$this->e87_data_dia);
         $this->e87_data_mes = ($this->e87_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e87_data_mes"]:$this->e87_data_mes);
         $this->e87_data_ano = ($this->e87_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e87_data_ano"]:$this->e87_data_ano);
         if($this->e87_data_dia != ""){
            $this->e87_data = $this->e87_data_ano."-".$this->e87_data_mes."-".$this->e87_data_dia;
         }
       }
       $this->e87_hora = ($this->e87_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["e87_hora"]:$this->e87_hora);
       if($this->e87_dataproc == ""){
         $this->e87_dataproc_dia = ($this->e87_dataproc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e87_dataproc_dia"]:$this->e87_dataproc_dia);
         $this->e87_dataproc_mes = ($this->e87_dataproc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e87_dataproc_mes"]:$this->e87_dataproc_mes);
         $this->e87_dataproc_ano = ($this->e87_dataproc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e87_dataproc_ano"]:$this->e87_dataproc_ano);
         if($this->e87_dataproc_dia != ""){
            $this->e87_dataproc = $this->e87_dataproc_ano."-".$this->e87_dataproc_mes."-".$this->e87_dataproc_dia;
         }
       }
     }else{
       $this->e87_codgera = ($this->e87_codgera == ""?@$GLOBALS["HTTP_POST_VARS"]["e87_codgera"]:$this->e87_codgera);
     }
   }
   // funcao para inclusao
   function incluir ($e87_codgera){ 
      $this->atualizacampos();
     if($this->e87_descgera == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "e87_descgera";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e87_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "e87_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e87_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "e87_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e87_dataproc == null ){ 
       $this->erro_sql = " Campo Autoriza pgto. nao Informado.";
       $this->erro_campo = "e87_dataproc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e87_codgera == "" || $e87_codgera == null ){
       $result = db_query("select nextval('empagegera_e87_codgera_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empagegera_e87_codgera_seq do campo: e87_codgera"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e87_codgera = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from empagegera_e87_codgera_seq");
       if(($result != false) && (pg_result($result,0,0) < $e87_codgera)){
         $this->erro_sql = " Campo e87_codgera maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e87_codgera = $e87_codgera; 
       }
     }
     if(($this->e87_codgera == null) || ($this->e87_codgera == "") ){ 
       $this->erro_sql = " Campo e87_codgera nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empagegera(
                                       e87_codgera 
                                      ,e87_descgera 
                                      ,e87_data 
                                      ,e87_hora 
                                      ,e87_dataproc 
                       )
                values (
                                $this->e87_codgera 
                               ,'$this->e87_descgera' 
                               ,".($this->e87_data == "null" || $this->e87_data == ""?"null":"'".$this->e87_data."'")." 
                               ,'$this->e87_hora' 
                               ,".($this->e87_dataproc == "null" || $this->e87_dataproc == ""?"null":"'".$this->e87_dataproc."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Gera agendas ($this->e87_codgera) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Gera agendas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Gera agendas ($this->e87_codgera) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e87_codgera;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e87_codgera));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6194,'$this->e87_codgera','I')");
       $resac = db_query("insert into db_acount values($acount,1002,6194,'','".AddSlashes(pg_result($resaco,0,'e87_codgera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1002,6195,'','".AddSlashes(pg_result($resaco,0,'e87_descgera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1002,6196,'','".AddSlashes(pg_result($resaco,0,'e87_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1002,6197,'','".AddSlashes(pg_result($resaco,0,'e87_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1002,7235,'','".AddSlashes(pg_result($resaco,0,'e87_dataproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e87_codgera=null) { 
      $this->atualizacampos();
     $sql = " update empagegera set ";
     $virgula = "";
     if(trim($this->e87_codgera)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e87_codgera"])){ 
       $sql  .= $virgula." e87_codgera = $this->e87_codgera ";
       $virgula = ",";
       if(trim($this->e87_codgera) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "e87_codgera";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e87_descgera)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e87_descgera"])){ 
       $sql  .= $virgula." e87_descgera = '$this->e87_descgera' ";
       $virgula = ",";
       if(trim($this->e87_descgera) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "e87_descgera";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e87_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e87_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e87_data_dia"] !="") ){ 
       $sql  .= $virgula." e87_data = '$this->e87_data' ";
       $virgula = ",";
       if(trim($this->e87_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "e87_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e87_data_dia"])){ 
         $sql  .= $virgula." e87_data = null ";
         $virgula = ",";
         if(trim($this->e87_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "e87_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->e87_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e87_hora"])){ 
       $sql  .= $virgula." e87_hora = '$this->e87_hora' ";
       $virgula = ",";
       if(trim($this->e87_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "e87_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e87_dataproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e87_dataproc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e87_dataproc_dia"] !="") ){ 
       $sql  .= $virgula." e87_dataproc = '$this->e87_dataproc' ";
       $virgula = ",";
       if(trim($this->e87_dataproc) == null ){ 
         $this->erro_sql = " Campo Autoriza pgto. nao Informado.";
         $this->erro_campo = "e87_dataproc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e87_dataproc_dia"])){ 
         $sql  .= $virgula." e87_dataproc = null ";
         $virgula = ",";
         if(trim($this->e87_dataproc) == null ){ 
           $this->erro_sql = " Campo Autoriza pgto. nao Informado.";
           $this->erro_campo = "e87_dataproc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($e87_codgera!=null){
       $sql .= " e87_codgera = $this->e87_codgera";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e87_codgera));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6194,'$this->e87_codgera','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e87_codgera"]))
           $resac = db_query("insert into db_acount values($acount,1002,6194,'".AddSlashes(pg_result($resaco,$conresaco,'e87_codgera'))."','$this->e87_codgera',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e87_descgera"]))
           $resac = db_query("insert into db_acount values($acount,1002,6195,'".AddSlashes(pg_result($resaco,$conresaco,'e87_descgera'))."','$this->e87_descgera',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e87_data"]))
           $resac = db_query("insert into db_acount values($acount,1002,6196,'".AddSlashes(pg_result($resaco,$conresaco,'e87_data'))."','$this->e87_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e87_hora"]))
           $resac = db_query("insert into db_acount values($acount,1002,6197,'".AddSlashes(pg_result($resaco,$conresaco,'e87_hora'))."','$this->e87_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e87_dataproc"]))
           $resac = db_query("insert into db_acount values($acount,1002,7235,'".AddSlashes(pg_result($resaco,$conresaco,'e87_dataproc'))."','$this->e87_dataproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Gera agendas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e87_codgera;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Gera agendas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e87_codgera;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e87_codgera;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e87_codgera=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e87_codgera));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6194,'$e87_codgera','E')");
         $resac = db_query("insert into db_acount values($acount,1002,6194,'','".AddSlashes(pg_result($resaco,$iresaco,'e87_codgera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1002,6195,'','".AddSlashes(pg_result($resaco,$iresaco,'e87_descgera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1002,6196,'','".AddSlashes(pg_result($resaco,$iresaco,'e87_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1002,6197,'','".AddSlashes(pg_result($resaco,$iresaco,'e87_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1002,7235,'','".AddSlashes(pg_result($resaco,$iresaco,'e87_dataproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empagegera
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e87_codgera != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e87_codgera = $e87_codgera ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Gera agendas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e87_codgera;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Gera agendas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e87_codgera;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e87_codgera;
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
        $this->erro_sql   = "Record Vazio na Tabela:empagegera";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e87_codgera=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagegera ";
     $sql2 = "";
     if($dbwhere==""){
       if($e87_codgera!=null ){
         $sql2 .= " where empagegera.e87_codgera = $e87_codgera "; 
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
   function sql_query_file ( $e87_codgera=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagegera ";
     $sql2 = "";
     if($dbwhere==""){
       if($e87_codgera!=null ){
         $sql2 .= " where empagegera.e87_codgera = $e87_codgera "; 
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
   function sql_query_inner ( $e87_codgera=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empagegera                                                                     ";
     $sql2 = "";
     $sql .= " inner join empageconfgera                                                           ";
     $sql .= "       on empageconfgera.e90_codgera = empagegera.e87_codgera                        ";
     $sql .= " inner join empageconf                                                               ";
     $sql .= "       on empageconf.e86_codmov = empageconfgera.e90_codmov                          ";
     $sql .= " inner join empagemov                                                                ";
     $sql .= "       on empagemov.e81_codmov = empageconfgera.e90_codmov                           ";
     $sql .= " inner join empage  on  empage.e80_codage = empagemov.e81_codage                     ";
     $sql .= " left join empempenho                                                                ";
     $sql .= "       on empempenho.e60_numemp = empagemov.e81_numemp                               ";
     $sql .= " left join cgm                                                                       ";
     $sql .= "      on cgm.z01_numcgm = empempenho.e60_numcgm                                      ";
		 $sql .= " left join empageconfche                                                             ";
		 $sql .= "      on empageconfche.e91_codmov = empagemov.e81_codmov and e91_ativo is true       ";
		 $sql .= " left join empagedadosret on empageconfgera.e90_codgera = empagedadosret.e75_codgera ";
		 
     if($dbwhere==""){
       if($e87_codgera!=null ){
         $sql2 .= " where empagegera.e87_codgera = $e87_codgera ";
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