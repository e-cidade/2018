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

//MODULO: Acordos
//CLASSE DA ENTIDADE acordocomissaomembro
class cl_acordocomissaomembro { 
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
   var $ac07_sequencial = 0; 
   var $ac07_acordocomissao = 0; 
   var $ac07_numcgm = 0; 
   var $ac07_tipomembro = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac07_sequencial = int4 = Sequencial 
                 ac07_acordocomissao = int4 = Acordo Comissão 
                 ac07_numcgm = int4 = Número CGM 
                 ac07_tipomembro = int4 = Tipo Membro 
                 ";
   //funcao construtor da classe 
   function cl_acordocomissaomembro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordocomissaomembro"); 
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
       $this->ac07_sequencial = ($this->ac07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac07_sequencial"]:$this->ac07_sequencial);
       $this->ac07_acordocomissao = ($this->ac07_acordocomissao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac07_acordocomissao"]:$this->ac07_acordocomissao);
       $this->ac07_numcgm = ($this->ac07_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["ac07_numcgm"]:$this->ac07_numcgm);
       $this->ac07_tipomembro = ($this->ac07_tipomembro == ""?@$GLOBALS["HTTP_POST_VARS"]["ac07_tipomembro"]:$this->ac07_tipomembro);
     }else{
       $this->ac07_sequencial = ($this->ac07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac07_sequencial"]:$this->ac07_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac07_sequencial){ 
      $this->atualizacampos();
     if($this->ac07_acordocomissao == null ){ 
       $this->erro_sql = " Campo Acordo Comissão nao Informado.";
       $this->erro_campo = "ac07_acordocomissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac07_numcgm == null ){ 
       $this->erro_sql = " Campo Número CGM nao Informado.";
       $this->erro_campo = "ac07_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac07_tipomembro == null ){ 
       $this->erro_sql = " Campo Tipo Membro nao Informado.";
       $this->erro_campo = "ac07_tipomembro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac07_sequencial == "" || $ac07_sequencial == null ){
       $result = db_query("select nextval('acordocomissaomembro_ac07_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordocomissaomembro_ac07_sequencial_seq do campo: ac07_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac07_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordocomissaomembro_ac07_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac07_sequencial)){
         $this->erro_sql = " Campo ac07_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac07_sequencial = $ac07_sequencial; 
       }
     }
     if(($this->ac07_sequencial == null) || ($this->ac07_sequencial == "") ){ 
       $this->erro_sql = " Campo ac07_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordocomissaomembro(
                                       ac07_sequencial 
                                      ,ac07_acordocomissao 
                                      ,ac07_numcgm 
                                      ,ac07_tipomembro 
                       )
                values (
                                $this->ac07_sequencial 
                               ,$this->ac07_acordocomissao 
                               ,$this->ac07_numcgm 
                               ,$this->ac07_tipomembro 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo Comissão Membro ($this->ac07_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo Comissão Membro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo Comissão Membro ($this->ac07_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac07_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac07_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16141,'$this->ac07_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2831,16141,'','".AddSlashes(pg_result($resaco,0,'ac07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2831,16142,'','".AddSlashes(pg_result($resaco,0,'ac07_acordocomissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2831,16143,'','".AddSlashes(pg_result($resaco,0,'ac07_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2831,16144,'','".AddSlashes(pg_result($resaco,0,'ac07_tipomembro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac07_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordocomissaomembro set ";
     $virgula = "";
     if(trim($this->ac07_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac07_sequencial"])){ 
       $sql  .= $virgula." ac07_sequencial = $this->ac07_sequencial ";
       $virgula = ",";
       if(trim($this->ac07_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac07_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac07_acordocomissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac07_acordocomissao"])){ 
       $sql  .= $virgula." ac07_acordocomissao = $this->ac07_acordocomissao ";
       $virgula = ",";
       if(trim($this->ac07_acordocomissao) == null ){ 
         $this->erro_sql = " Campo Acordo Comissão nao Informado.";
         $this->erro_campo = "ac07_acordocomissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac07_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac07_numcgm"])){ 
       $sql  .= $virgula." ac07_numcgm = $this->ac07_numcgm ";
       $virgula = ",";
       if(trim($this->ac07_numcgm) == null ){ 
         $this->erro_sql = " Campo Número CGM nao Informado.";
         $this->erro_campo = "ac07_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac07_tipomembro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac07_tipomembro"])){ 
       $sql  .= $virgula." ac07_tipomembro = $this->ac07_tipomembro ";
       $virgula = ",";
       if(trim($this->ac07_tipomembro) == null ){ 
         $this->erro_sql = " Campo Tipo Membro nao Informado.";
         $this->erro_campo = "ac07_tipomembro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac07_sequencial!=null){
       $sql .= " ac07_sequencial = $this->ac07_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac07_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16141,'$this->ac07_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac07_sequencial"]) || $this->ac07_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2831,16141,'".AddSlashes(pg_result($resaco,$conresaco,'ac07_sequencial'))."','$this->ac07_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac07_acordocomissao"]) || $this->ac07_acordocomissao != "")
           $resac = db_query("insert into db_acount values($acount,2831,16142,'".AddSlashes(pg_result($resaco,$conresaco,'ac07_acordocomissao'))."','$this->ac07_acordocomissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac07_numcgm"]) || $this->ac07_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,2831,16143,'".AddSlashes(pg_result($resaco,$conresaco,'ac07_numcgm'))."','$this->ac07_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac07_tipomembro"]) || $this->ac07_tipomembro != "")
           $resac = db_query("insert into db_acount values($acount,2831,16144,'".AddSlashes(pg_result($resaco,$conresaco,'ac07_tipomembro'))."','$this->ac07_tipomembro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Comissão Membro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Comissão Membro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac07_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac07_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16141,'$ac07_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2831,16141,'','".AddSlashes(pg_result($resaco,$iresaco,'ac07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2831,16142,'','".AddSlashes(pg_result($resaco,$iresaco,'ac07_acordocomissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2831,16143,'','".AddSlashes(pg_result($resaco,$iresaco,'ac07_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2831,16144,'','".AddSlashes(pg_result($resaco,$iresaco,'ac07_tipomembro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordocomissaomembro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac07_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac07_sequencial = $ac07_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Comissão Membro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Comissão Membro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac07_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordocomissaomembro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordocomissaomembro ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordocomissaomembro.ac07_numcgm";
     $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = acordocomissaomembro.ac07_acordocomissao";
     $sql2 = "";
     if($dbwhere==""){
       if($ac07_sequencial!=null ){
         $sql2 .= " where acordocomissaomembro.ac07_sequencial = $ac07_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $ac07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordocomissaomembro ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac07_sequencial!=null ){
         $sql2 .= " where acordocomissaomembro.ac07_sequencial = $ac07_sequencial "; 
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