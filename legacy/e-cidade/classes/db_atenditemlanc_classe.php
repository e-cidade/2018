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

//MODULO: atendimento
//CLASSE DA ENTIDADE atenditemlanc
class cl_atenditemlanc { 
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
   var $at07_atenditem = 0; 
   var $at07_usuariolanc = 0; 
   var $at07_datalanc_dia = null; 
   var $at07_datalanc_mes = null; 
   var $at07_datalanc_ano = null; 
   var $at07_datalanc = null; 
   var $at07_horalanc = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at07_atenditem = int4 = Sequência 
                 at07_usuariolanc = int4 = Cod. Usuário 
                 at07_datalanc = date = Data de lancamento 
                 at07_horalanc = char(5) = Hora do lancamento 
                 ";
   //funcao construtor da classe 
   function cl_atenditemlanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atenditemlanc"); 
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
       $this->at07_atenditem = ($this->at07_atenditem == ""?@$GLOBALS["HTTP_POST_VARS"]["at07_atenditem"]:$this->at07_atenditem);
       $this->at07_usuariolanc = ($this->at07_usuariolanc == ""?@$GLOBALS["HTTP_POST_VARS"]["at07_usuariolanc"]:$this->at07_usuariolanc);
       if($this->at07_datalanc == ""){
         $this->at07_datalanc_dia = ($this->at07_datalanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at07_datalanc_dia"]:$this->at07_datalanc_dia);
         $this->at07_datalanc_mes = ($this->at07_datalanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at07_datalanc_mes"]:$this->at07_datalanc_mes);
         $this->at07_datalanc_ano = ($this->at07_datalanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at07_datalanc_ano"]:$this->at07_datalanc_ano);
         if($this->at07_datalanc_dia != ""){
            $this->at07_datalanc = $this->at07_datalanc_ano."-".$this->at07_datalanc_mes."-".$this->at07_datalanc_dia;
         }
       }
       $this->at07_horalanc = ($this->at07_horalanc == ""?@$GLOBALS["HTTP_POST_VARS"]["at07_horalanc"]:$this->at07_horalanc);
     }else{
       $this->at07_atenditem = ($this->at07_atenditem == ""?@$GLOBALS["HTTP_POST_VARS"]["at07_atenditem"]:$this->at07_atenditem);
     }
   }
   // funcao para inclusao
   function incluir ($at07_atenditem){ 
      $this->atualizacampos();
     if($this->at07_usuariolanc == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "at07_usuariolanc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at07_datalanc == null ){ 
       $this->erro_sql = " Campo Data de lancamento nao Informado.";
       $this->erro_campo = "at07_datalanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at07_horalanc == null ){ 
       $this->erro_sql = " Campo Hora do lancamento nao Informado.";
       $this->erro_campo = "at07_horalanc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->at07_atenditem = $at07_atenditem; 
     if(($this->at07_atenditem == null) || ($this->at07_atenditem == "") ){ 
       $this->erro_sql = " Campo at07_atenditem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atenditemlanc(
                                       at07_atenditem 
                                      ,at07_usuariolanc 
                                      ,at07_datalanc 
                                      ,at07_horalanc 
                       )
                values (
                                $this->at07_atenditem 
                               ,$this->at07_usuariolanc 
                               ,".($this->at07_datalanc == "null" || $this->at07_datalanc == ""?"null":"'".$this->at07_datalanc."'")." 
                               ,'$this->at07_horalanc' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lancamentos do item de atendimento ($this->at07_atenditem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lancamentos do item de atendimento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lancamentos do item de atendimento ($this->at07_atenditem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at07_atenditem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at07_atenditem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8308,'$this->at07_atenditem','I')");
       $resac = db_query("insert into db_acount values($acount,1403,8308,'','".AddSlashes(pg_result($resaco,0,'at07_atenditem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1403,8309,'','".AddSlashes(pg_result($resaco,0,'at07_usuariolanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1403,8310,'','".AddSlashes(pg_result($resaco,0,'at07_datalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1403,8311,'','".AddSlashes(pg_result($resaco,0,'at07_horalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at07_atenditem=null) { 
      $this->atualizacampos();
     $sql = " update atenditemlanc set ";
     $virgula = "";
     if(trim($this->at07_atenditem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at07_atenditem"])){ 
       $sql  .= $virgula." at07_atenditem = $this->at07_atenditem ";
       $virgula = ",";
       if(trim($this->at07_atenditem) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "at07_atenditem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at07_usuariolanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at07_usuariolanc"])){ 
       $sql  .= $virgula." at07_usuariolanc = $this->at07_usuariolanc ";
       $virgula = ",";
       if(trim($this->at07_usuariolanc) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "at07_usuariolanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at07_datalanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at07_datalanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at07_datalanc_dia"] !="") ){ 
       $sql  .= $virgula." at07_datalanc = '$this->at07_datalanc' ";
       $virgula = ",";
       if(trim($this->at07_datalanc) == null ){ 
         $this->erro_sql = " Campo Data de lancamento nao Informado.";
         $this->erro_campo = "at07_datalanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at07_datalanc_dia"])){ 
         $sql  .= $virgula." at07_datalanc = null ";
         $virgula = ",";
         if(trim($this->at07_datalanc) == null ){ 
           $this->erro_sql = " Campo Data de lancamento nao Informado.";
           $this->erro_campo = "at07_datalanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at07_horalanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at07_horalanc"])){ 
       $sql  .= $virgula." at07_horalanc = '$this->at07_horalanc' ";
       $virgula = ",";
       if(trim($this->at07_horalanc) == null ){ 
         $this->erro_sql = " Campo Hora do lancamento nao Informado.";
         $this->erro_campo = "at07_horalanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at07_atenditem!=null){
       $sql .= " at07_atenditem = $this->at07_atenditem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at07_atenditem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8308,'$this->at07_atenditem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at07_atenditem"]))
           $resac = db_query("insert into db_acount values($acount,1403,8308,'".AddSlashes(pg_result($resaco,$conresaco,'at07_atenditem'))."','$this->at07_atenditem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at07_usuariolanc"]))
           $resac = db_query("insert into db_acount values($acount,1403,8309,'".AddSlashes(pg_result($resaco,$conresaco,'at07_usuariolanc'))."','$this->at07_usuariolanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at07_datalanc"]))
           $resac = db_query("insert into db_acount values($acount,1403,8310,'".AddSlashes(pg_result($resaco,$conresaco,'at07_datalanc'))."','$this->at07_datalanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at07_horalanc"]))
           $resac = db_query("insert into db_acount values($acount,1403,8311,'".AddSlashes(pg_result($resaco,$conresaco,'at07_horalanc'))."','$this->at07_horalanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lancamentos do item de atendimento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at07_atenditem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lancamentos do item de atendimento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at07_atenditem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at07_atenditem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at07_atenditem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at07_atenditem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8308,'$at07_atenditem','E')");
         $resac = db_query("insert into db_acount values($acount,1403,8308,'','".AddSlashes(pg_result($resaco,$iresaco,'at07_atenditem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1403,8309,'','".AddSlashes(pg_result($resaco,$iresaco,'at07_usuariolanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1403,8310,'','".AddSlashes(pg_result($resaco,$iresaco,'at07_datalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1403,8311,'','".AddSlashes(pg_result($resaco,$iresaco,'at07_horalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atenditemlanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at07_atenditem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at07_atenditem = $at07_atenditem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lancamentos do item de atendimento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at07_atenditem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lancamentos do item de atendimento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at07_atenditem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at07_atenditem;
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
        $this->erro_sql   = "Record Vazio na Tabela:atenditemlanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at07_atenditem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atenditemlanc ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = atenditemlanc.at07_usuariolanc";
     $sql .= "      inner join atenditem  on  atenditem.at05_seq = atenditemlanc.at07_atenditem";
     $sql .= "      inner join atendimento  on  atendimento.at02_codatend = atenditem.at05_codatend";
     $sql2 = "";
     if($dbwhere==""){
       if($at07_atenditem!=null ){
         $sql2 .= " where atenditemlanc.at07_atenditem = $at07_atenditem "; 
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
   function sql_query_file ( $at07_atenditem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atenditemlanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($at07_atenditem!=null ){
         $sql2 .= " where atenditemlanc.at07_atenditem = $at07_atenditem "; 
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