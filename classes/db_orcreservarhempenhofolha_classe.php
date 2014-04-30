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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcreservarhempenhofolha
class cl_orcreservarhempenhofolha { 
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
   var $o120_sequencial = 0; 
   var $o120_orcreserva = 0; 
   var $o120_rhempenhofolha = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o120_sequencial = int4 = Código da Reserva 
                 o120_orcreserva = int8 = Código da Reserva 
                 o120_rhempenhofolha = int4 = Código Empenho 
                 ";
   //funcao construtor da classe 
   function cl_orcreservarhempenhofolha() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcreservarhempenhofolha"); 
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
       $this->o120_sequencial = ($this->o120_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o120_sequencial"]:$this->o120_sequencial);
       $this->o120_orcreserva = ($this->o120_orcreserva == ""?@$GLOBALS["HTTP_POST_VARS"]["o120_orcreserva"]:$this->o120_orcreserva);
       $this->o120_rhempenhofolha = ($this->o120_rhempenhofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["o120_rhempenhofolha"]:$this->o120_rhempenhofolha);
     }else{
       $this->o120_sequencial = ($this->o120_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o120_sequencial"]:$this->o120_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o120_sequencial){ 
      $this->atualizacampos();
     if($this->o120_orcreserva == null ){ 
       $this->erro_sql = " Campo Código da Reserva nao Informado.";
       $this->erro_campo = "o120_orcreserva";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o120_rhempenhofolha == null ){ 
       $this->erro_sql = " Campo Código Empenho nao Informado.";
       $this->erro_campo = "o120_rhempenhofolha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o120_sequencial == "" || $o120_sequencial == null ){
       $result = db_query("select nextval('orcreservarhempenhofolha_o120_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcreservarhempenhofolha_o120_sequencial_seq do campo: o120_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o120_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcreservarhempenhofolha_o120_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o120_sequencial)){
         $this->erro_sql = " Campo o120_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o120_sequencial = $o120_sequencial; 
       }
     }
     if(($this->o120_sequencial == null) || ($this->o120_sequencial == "") ){ 
       $this->erro_sql = " Campo o120_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcreservarhempenhofolha(
                                       o120_sequencial 
                                      ,o120_orcreserva 
                                      ,o120_rhempenhofolha 
                       )
                values (
                                $this->o120_sequencial 
                               ,$this->o120_orcreserva 
                               ,$this->o120_rhempenhofolha 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Reservas de saldo para folha ($this->o120_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Reservas de saldo para folha já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Reservas de saldo para folha ($this->o120_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o120_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o120_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14390,'$this->o120_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2537,14390,'','".AddSlashes(pg_result($resaco,0,'o120_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2537,14391,'','".AddSlashes(pg_result($resaco,0,'o120_orcreserva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2537,14392,'','".AddSlashes(pg_result($resaco,0,'o120_rhempenhofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o120_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcreservarhempenhofolha set ";
     $virgula = "";
     if(trim($this->o120_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o120_sequencial"])){ 
       $sql  .= $virgula." o120_sequencial = $this->o120_sequencial ";
       $virgula = ",";
       if(trim($this->o120_sequencial) == null ){ 
         $this->erro_sql = " Campo Código da Reserva nao Informado.";
         $this->erro_campo = "o120_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o120_orcreserva)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o120_orcreserva"])){ 
       $sql  .= $virgula." o120_orcreserva = $this->o120_orcreserva ";
       $virgula = ",";
       if(trim($this->o120_orcreserva) == null ){ 
         $this->erro_sql = " Campo Código da Reserva nao Informado.";
         $this->erro_campo = "o120_orcreserva";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o120_rhempenhofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o120_rhempenhofolha"])){ 
       $sql  .= $virgula." o120_rhempenhofolha = $this->o120_rhempenhofolha ";
       $virgula = ",";
       if(trim($this->o120_rhempenhofolha) == null ){ 
         $this->erro_sql = " Campo Código Empenho nao Informado.";
         $this->erro_campo = "o120_rhempenhofolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o120_sequencial!=null){
       $sql .= " o120_sequencial = $this->o120_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o120_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14390,'$this->o120_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o120_sequencial"]) || $this->o120_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2537,14390,'".AddSlashes(pg_result($resaco,$conresaco,'o120_sequencial'))."','$this->o120_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o120_orcreserva"]) || $this->o120_orcreserva != "")
           $resac = db_query("insert into db_acount values($acount,2537,14391,'".AddSlashes(pg_result($resaco,$conresaco,'o120_orcreserva'))."','$this->o120_orcreserva',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o120_rhempenhofolha"]) || $this->o120_rhempenhofolha != "")
           $resac = db_query("insert into db_acount values($acount,2537,14392,'".AddSlashes(pg_result($resaco,$conresaco,'o120_rhempenhofolha'))."','$this->o120_rhempenhofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reservas de saldo para folha nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o120_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Reservas de saldo para folha nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o120_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o120_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o120_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o120_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14390,'$o120_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2537,14390,'','".AddSlashes(pg_result($resaco,$iresaco,'o120_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2537,14391,'','".AddSlashes(pg_result($resaco,$iresaco,'o120_orcreserva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2537,14392,'','".AddSlashes(pg_result($resaco,$iresaco,'o120_rhempenhofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcreservarhempenhofolha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o120_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o120_sequencial = $o120_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reservas de saldo para folha nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o120_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Reservas de saldo para folha nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o120_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o120_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcreservarhempenhofolha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o120_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcreservarhempenhofolha ";
     $sql .= "      inner join orcreserva  on  orcreserva.o80_codres = orcreservarhempenhofolha.o120_orcreserva";
     $sql .= "      inner join rhempenhofolha  on  rhempenhofolha.rh72_sequencial = orcreservarhempenhofolha.o120_rhempenhofolha";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = orcreserva.o80_anousu and  orcdotacao.o58_coddot = orcreserva.o80_coddot";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = rhempenhofolha.rh72_recurso";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = rhempenhofolha.rh72_codele and  orcelemento.o56_anousu = rhempenhofolha.rh72_anousu";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = rhempenhofolha.rh72_anousu and  orcprojativ.o55_projativ = rhempenhofolha.rh72_projativ";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = rhempenhofolha.rh72_anousu and  orcunidade.o41_orgao = rhempenhofolha.rh72_orgao and  orcunidade.o41_unidade = rhempenhofolha.rh72_unidade";
     $sql .= "      inner join orcdotacao  as a on   a.o58_anousu = rhempenhofolha.rh72_coddot and   a.o58_coddot = rhempenhofolha.rh72_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($o120_sequencial!=null ){
         $sql2 .= " where orcreservarhempenhofolha.o120_sequencial = $o120_sequencial "; 
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
   function sql_query_file ( $o120_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcreservarhempenhofolha ";
     $sql2 = "";
     if($dbwhere==""){
       if($o120_sequencial!=null ){
         $sql2 .= " where orcreservarhempenhofolha.o120_sequencial = $o120_sequencial "; 
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