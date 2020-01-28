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
//CLASSE DA ENTIDADE atendimentolanc
class cl_atendimentolanc { 
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
   var $at06_codatend = 0; 
   var $at06_usuariolanc = 0; 
   var $at06_datalanc_dia = null; 
   var $at06_datalanc_mes = null; 
   var $at06_datalanc_ano = null; 
   var $at06_datalanc = null; 
   var $at06_horalanc = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at06_codatend = int4 = Código de atendimento 
                 at06_usuariolanc = int4 = Usuário que lancou o atendimento 
                 at06_datalanc = date = Data de lancamento 
                 at06_horalanc = char(5) = Hora do lancamento 
                 ";
   //funcao construtor da classe 
   function cl_atendimentolanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atendimentolanc"); 
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
       $this->at06_codatend = ($this->at06_codatend == ""?@$GLOBALS["HTTP_POST_VARS"]["at06_codatend"]:$this->at06_codatend);
       $this->at06_usuariolanc = ($this->at06_usuariolanc == ""?@$GLOBALS["HTTP_POST_VARS"]["at06_usuariolanc"]:$this->at06_usuariolanc);
       if($this->at06_datalanc == ""){
         $this->at06_datalanc_dia = ($this->at06_datalanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at06_datalanc_dia"]:$this->at06_datalanc_dia);
         $this->at06_datalanc_mes = ($this->at06_datalanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at06_datalanc_mes"]:$this->at06_datalanc_mes);
         $this->at06_datalanc_ano = ($this->at06_datalanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at06_datalanc_ano"]:$this->at06_datalanc_ano);
         if($this->at06_datalanc_dia != ""){
            $this->at06_datalanc = $this->at06_datalanc_ano."-".$this->at06_datalanc_mes."-".$this->at06_datalanc_dia;
         }
       }
       $this->at06_horalanc = ($this->at06_horalanc == ""?@$GLOBALS["HTTP_POST_VARS"]["at06_horalanc"]:$this->at06_horalanc);
     }else{
       $this->at06_codatend = ($this->at06_codatend == ""?@$GLOBALS["HTTP_POST_VARS"]["at06_codatend"]:$this->at06_codatend);
     }
   }
   // funcao para inclusao
   function incluir ($at06_codatend){ 
      $this->atualizacampos();
     if($this->at06_usuariolanc == null ){ 
       $this->erro_sql = " Campo Usuário que lancou o atendimento nao Informado.";
       $this->erro_campo = "at06_usuariolanc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at06_datalanc == null ){ 
       $this->erro_sql = " Campo Data de lancamento nao Informado.";
       $this->erro_campo = "at06_datalanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at06_horalanc == null ){ 
       $this->erro_sql = " Campo Hora do lancamento nao Informado.";
       $this->erro_campo = "at06_horalanc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->at06_codatend = $at06_codatend; 
     if(($this->at06_codatend == null) || ($this->at06_codatend == "") ){ 
       $this->erro_sql = " Campo at06_codatend nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atendimentolanc(
                                       at06_codatend 
                                      ,at06_usuariolanc 
                                      ,at06_datalanc 
                                      ,at06_horalanc 
                       )
                values (
                                $this->at06_codatend 
                               ,$this->at06_usuariolanc 
                               ,".($this->at06_datalanc == "null" || $this->at06_datalanc == ""?"null":"'".$this->at06_datalanc."'")." 
                               ,'$this->at06_horalanc' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lancamento do atendimento ($this->at06_codatend) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lancamento do atendimento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lancamento do atendimento ($this->at06_codatend) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at06_codatend;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at06_codatend));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8307,'$this->at06_codatend','I')");
       $resac = db_query("insert into db_acount values($acount,1402,8307,'','".AddSlashes(pg_result($resaco,0,'at06_codatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1402,8304,'','".AddSlashes(pg_result($resaco,0,'at06_usuariolanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1402,8305,'','".AddSlashes(pg_result($resaco,0,'at06_datalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1402,8306,'','".AddSlashes(pg_result($resaco,0,'at06_horalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at06_codatend=null) { 
      $this->atualizacampos();
     $sql = " update atendimentolanc set ";
     $virgula = "";
     if(trim($this->at06_codatend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at06_codatend"])){ 
       $sql  .= $virgula." at06_codatend = $this->at06_codatend ";
       $virgula = ",";
       if(trim($this->at06_codatend) == null ){ 
         $this->erro_sql = " Campo Código de atendimento nao Informado.";
         $this->erro_campo = "at06_codatend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at06_usuariolanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at06_usuariolanc"])){ 
       $sql  .= $virgula." at06_usuariolanc = $this->at06_usuariolanc ";
       $virgula = ",";
       if(trim($this->at06_usuariolanc) == null ){ 
         $this->erro_sql = " Campo Usuário que lancou o atendimento nao Informado.";
         $this->erro_campo = "at06_usuariolanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at06_datalanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at06_datalanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at06_datalanc_dia"] !="") ){ 
       $sql  .= $virgula." at06_datalanc = '$this->at06_datalanc' ";
       $virgula = ",";
       if(trim($this->at06_datalanc) == null ){ 
         $this->erro_sql = " Campo Data de lancamento nao Informado.";
         $this->erro_campo = "at06_datalanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at06_datalanc_dia"])){ 
         $sql  .= $virgula." at06_datalanc = null ";
         $virgula = ",";
         if(trim($this->at06_datalanc) == null ){ 
           $this->erro_sql = " Campo Data de lancamento nao Informado.";
           $this->erro_campo = "at06_datalanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at06_horalanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at06_horalanc"])){ 
       $sql  .= $virgula." at06_horalanc = '$this->at06_horalanc' ";
       $virgula = ",";
       if(trim($this->at06_horalanc) == null ){ 
         $this->erro_sql = " Campo Hora do lancamento nao Informado.";
         $this->erro_campo = "at06_horalanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at06_codatend!=null){
       $sql .= " at06_codatend = $this->at06_codatend";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at06_codatend));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8307,'$this->at06_codatend','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at06_codatend"]))
           $resac = db_query("insert into db_acount values($acount,1402,8307,'".AddSlashes(pg_result($resaco,$conresaco,'at06_codatend'))."','$this->at06_codatend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at06_usuariolanc"]))
           $resac = db_query("insert into db_acount values($acount,1402,8304,'".AddSlashes(pg_result($resaco,$conresaco,'at06_usuariolanc'))."','$this->at06_usuariolanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at06_datalanc"]))
           $resac = db_query("insert into db_acount values($acount,1402,8305,'".AddSlashes(pg_result($resaco,$conresaco,'at06_datalanc'))."','$this->at06_datalanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at06_horalanc"]))
           $resac = db_query("insert into db_acount values($acount,1402,8306,'".AddSlashes(pg_result($resaco,$conresaco,'at06_horalanc'))."','$this->at06_horalanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lancamento do atendimento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at06_codatend;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lancamento do atendimento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at06_codatend;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at06_codatend;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at06_codatend=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at06_codatend));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8307,'$at06_codatend','E')");
         $resac = db_query("insert into db_acount values($acount,1402,8307,'','".AddSlashes(pg_result($resaco,$iresaco,'at06_codatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1402,8304,'','".AddSlashes(pg_result($resaco,$iresaco,'at06_usuariolanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1402,8305,'','".AddSlashes(pg_result($resaco,$iresaco,'at06_datalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1402,8306,'','".AddSlashes(pg_result($resaco,$iresaco,'at06_horalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atendimentolanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at06_codatend != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at06_codatend = $at06_codatend ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lancamento do atendimento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at06_codatend;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lancamento do atendimento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at06_codatend;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at06_codatend;
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
        $this->erro_sql   = "Record Vazio na Tabela:atendimentolanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at06_codatend=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendimentolanc ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = atendimentolanc.at06_usuariolanc";
     $sql .= "      inner join atendimento  on  atendimento.at02_codatend = atendimentolanc.at06_codatend";
     $sql .= "      inner join clientes  on  clientes.at01_codcli = atendimento.at02_codcli";
     $sql .= "      inner join tipoatend  on  tipoatend.at04_codtipo = atendimento.at02_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($at06_codatend!=null ){
         $sql2 .= " where atendimentolanc.at06_codatend = $at06_codatend "; 
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
   function sql_query_file ( $at06_codatend=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendimentolanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($at06_codatend!=null ){
         $sql2 .= " where atendimentolanc.at06_codatend = $at06_codatend "; 
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