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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_viradaitemcanc
class cl_db_viradaitemcanc { 
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
   var $c32_sequencial = 0; 
   var $c32_db_viradaitem = 0; 
   var $c32_usuario = 0; 
   var $c32_data_dia = null; 
   var $c32_data_mes = null; 
   var $c32_data_ano = null; 
   var $c32_data = null; 
   var $c32_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c32_sequencial = int4 = Código 
                 c32_db_viradaitem = int4 = Iten 
                 c32_usuario = int4 = Usuário 
                 c32_data = date = Data 
                 c32_hora = char(5) = Hora 
                 ";
   //funcao construtor da classe 
   function cl_db_viradaitemcanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_viradaitemcanc"); 
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
       $this->c32_sequencial = ($this->c32_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c32_sequencial"]:$this->c32_sequencial);
       $this->c32_db_viradaitem = ($this->c32_db_viradaitem == ""?@$GLOBALS["HTTP_POST_VARS"]["c32_db_viradaitem"]:$this->c32_db_viradaitem);
       $this->c32_usuario = ($this->c32_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["c32_usuario"]:$this->c32_usuario);
       if($this->c32_data == ""){
         $this->c32_data_dia = ($this->c32_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c32_data_dia"]:$this->c32_data_dia);
         $this->c32_data_mes = ($this->c32_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c32_data_mes"]:$this->c32_data_mes);
         $this->c32_data_ano = ($this->c32_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c32_data_ano"]:$this->c32_data_ano);
         if($this->c32_data_dia != ""){
            $this->c32_data = $this->c32_data_ano."-".$this->c32_data_mes."-".$this->c32_data_dia;
         }
       }
       $this->c32_hora = ($this->c32_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["c32_hora"]:$this->c32_hora);
     }else{
       $this->c32_sequencial = ($this->c32_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c32_sequencial"]:$this->c32_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c32_sequencial){ 
      $this->atualizacampos();
     if($this->c32_db_viradaitem == null ){ 
       $this->erro_sql = " Campo Iten nao Informado.";
       $this->erro_campo = "c32_db_viradaitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c32_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "c32_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c32_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "c32_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c32_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "c32_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c32_sequencial == "" || $c32_sequencial == null ){
       $result = db_query("select nextval('db_viradaitemcanc_c32_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_viradaitemcanc_c32_sequencial_seq do campo: c32_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c32_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_viradaitemcanc_c32_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c32_sequencial)){
         $this->erro_sql = " Campo c32_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c32_sequencial = $c32_sequencial; 
       }
     }
     if(($this->c32_sequencial == null) || ($this->c32_sequencial == "") ){ 
       $this->erro_sql = " Campo c32_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_viradaitemcanc(
                                       c32_sequencial 
                                      ,c32_db_viradaitem 
                                      ,c32_usuario 
                                      ,c32_data 
                                      ,c32_hora 
                       )
                values (
                                $this->c32_sequencial 
                               ,$this->c32_db_viradaitem 
                               ,$this->c32_usuario 
                               ,".($this->c32_data == "null" || $this->c32_data == ""?"null":"'".$this->c32_data."'")." 
                               ,'$this->c32_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "viradaitemcanc ($this->c32_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "viradaitemcanc já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "viradaitemcanc ($this->c32_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c32_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c32_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10801,'$this->c32_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1859,10801,'','".AddSlashes(pg_result($resaco,0,'c32_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1859,10802,'','".AddSlashes(pg_result($resaco,0,'c32_db_viradaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1859,10803,'','".AddSlashes(pg_result($resaco,0,'c32_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1859,10804,'','".AddSlashes(pg_result($resaco,0,'c32_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1859,10805,'','".AddSlashes(pg_result($resaco,0,'c32_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c32_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_viradaitemcanc set ";
     $virgula = "";
     if(trim($this->c32_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c32_sequencial"])){ 
       $sql  .= $virgula." c32_sequencial = $this->c32_sequencial ";
       $virgula = ",";
       if(trim($this->c32_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "c32_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c32_db_viradaitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c32_db_viradaitem"])){ 
       $sql  .= $virgula." c32_db_viradaitem = $this->c32_db_viradaitem ";
       $virgula = ",";
       if(trim($this->c32_db_viradaitem) == null ){ 
         $this->erro_sql = " Campo Iten nao Informado.";
         $this->erro_campo = "c32_db_viradaitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c32_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c32_usuario"])){ 
       $sql  .= $virgula." c32_usuario = $this->c32_usuario ";
       $virgula = ",";
       if(trim($this->c32_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "c32_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c32_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c32_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c32_data_dia"] !="") ){ 
       $sql  .= $virgula." c32_data = '$this->c32_data' ";
       $virgula = ",";
       if(trim($this->c32_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "c32_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c32_data_dia"])){ 
         $sql  .= $virgula." c32_data = null ";
         $virgula = ",";
         if(trim($this->c32_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "c32_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c32_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c32_hora"])){ 
       $sql  .= $virgula." c32_hora = '$this->c32_hora' ";
       $virgula = ",";
       if(trim($this->c32_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "c32_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c32_sequencial!=null){
       $sql .= " c32_sequencial = $this->c32_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c32_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10801,'$this->c32_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c32_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1859,10801,'".AddSlashes(pg_result($resaco,$conresaco,'c32_sequencial'))."','$this->c32_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c32_db_viradaitem"]))
           $resac = db_query("insert into db_acount values($acount,1859,10802,'".AddSlashes(pg_result($resaco,$conresaco,'c32_db_viradaitem'))."','$this->c32_db_viradaitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c32_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1859,10803,'".AddSlashes(pg_result($resaco,$conresaco,'c32_usuario'))."','$this->c32_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c32_data"]))
           $resac = db_query("insert into db_acount values($acount,1859,10804,'".AddSlashes(pg_result($resaco,$conresaco,'c32_data'))."','$this->c32_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c32_hora"]))
           $resac = db_query("insert into db_acount values($acount,1859,10805,'".AddSlashes(pg_result($resaco,$conresaco,'c32_hora'))."','$this->c32_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "viradaitemcanc nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c32_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "viradaitemcanc nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c32_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c32_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10801,'$c32_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1859,10801,'','".AddSlashes(pg_result($resaco,$iresaco,'c32_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1859,10802,'','".AddSlashes(pg_result($resaco,$iresaco,'c32_db_viradaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1859,10803,'','".AddSlashes(pg_result($resaco,$iresaco,'c32_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1859,10804,'','".AddSlashes(pg_result($resaco,$iresaco,'c32_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1859,10805,'','".AddSlashes(pg_result($resaco,$iresaco,'c32_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_viradaitemcanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c32_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c32_sequencial = $c32_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "viradaitemcanc nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c32_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "viradaitemcanc nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c32_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_viradaitemcanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c32_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_viradaitemcanc ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_viradaitemcanc.c32_usuario";
     $sql .= "      inner join db_viradaitem  on  db_viradaitem.c31_sequencial = db_viradaitemcanc.c32_db_viradaitem";
     $sql .= "      inner join db_virada  as a on   a.c30_sequencial = db_viradaitem.c31_db_virada";
     $sql .= "      inner join db_viradacaditem  on  db_viradacaditem.c33_sequencial = db_viradaitem.c31_db_viradacaditem";
     $sql2 = "";
     if($dbwhere==""){
       if($c32_sequencial!=null ){
         $sql2 .= " where db_viradaitemcanc.c32_sequencial = $c32_sequencial "; 
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
   function sql_query_file ( $c32_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_viradaitemcanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($c32_sequencial!=null ){
         $sql2 .= " where db_viradaitemcanc.c32_sequencial = $c32_sequencial "; 
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