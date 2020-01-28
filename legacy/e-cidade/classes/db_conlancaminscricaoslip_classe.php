<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancaminscricaoslip
class cl_conlancaminscricaoslip { 
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
   var $c109_sequencial = 0; 
   var $c109_codlan = 0; 
   var $c109_inscricaopassiva = 0; 
   var $c109_slip = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c109_sequencial = int4 = Sequencial 
                 c109_codlan = int4 = C�digo Lan�amento 
                 c109_inscricaopassiva = int4 = Inscri��o Passiva 
                 c109_slip = int4 = C�digo Slip 
                 ";
   //funcao construtor da classe 
   function cl_conlancaminscricaoslip() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancaminscricaoslip"); 
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
       $this->c109_sequencial = ($this->c109_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c109_sequencial"]:$this->c109_sequencial);
       $this->c109_codlan = ($this->c109_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c109_codlan"]:$this->c109_codlan);
       $this->c109_inscricaopassiva = ($this->c109_inscricaopassiva == ""?@$GLOBALS["HTTP_POST_VARS"]["c109_inscricaopassiva"]:$this->c109_inscricaopassiva);
       $this->c109_slip = ($this->c109_slip == ""?@$GLOBALS["HTTP_POST_VARS"]["c109_slip"]:$this->c109_slip);
     }else{
       $this->c109_sequencial = ($this->c109_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c109_sequencial"]:$this->c109_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c109_sequencial){ 
      $this->atualizacampos();
     if($this->c109_codlan == null ){ 
       $this->erro_sql = " Campo C�digo Lan�amento nao Informado.";
       $this->erro_campo = "c109_codlan";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c109_inscricaopassiva == null ){ 
       $this->erro_sql = " Campo Inscri��o Passiva nao Informado.";
       $this->erro_campo = "c109_inscricaopassiva";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c109_slip == null ){ 
       $this->erro_sql = " Campo C�digo Slip nao Informado.";
       $this->erro_campo = "c109_slip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c109_sequencial == "" || $c109_sequencial == null ){
       $result = db_query("select nextval('conlancaminscricaoslip_c109_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conlancaminscricaoslip_c109_sequencial_seq do campo: c109_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c109_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from conlancaminscricaoslip_c109_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c109_sequencial)){
         $this->erro_sql = " Campo c109_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c109_sequencial = $c109_sequencial; 
       }
     }
     if(($this->c109_sequencial == null) || ($this->c109_sequencial == "") ){ 
       $this->erro_sql = " Campo c109_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancaminscricaoslip(
                                       c109_sequencial 
                                      ,c109_codlan 
                                      ,c109_inscricaopassiva 
                                      ,c109_slip 
                       )
                values (
                                $this->c109_sequencial 
                               ,$this->c109_codlan 
                               ,$this->c109_inscricaopassiva 
                               ,$this->c109_slip 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Slips inscri��o ($this->c109_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Slips inscri��o j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Slips inscri��o ($this->c109_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c109_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c109_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19627,'$this->c109_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3489,19627,'','".AddSlashes(pg_result($resaco,0,'c109_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3489,19628,'','".AddSlashes(pg_result($resaco,0,'c109_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3489,19629,'','".AddSlashes(pg_result($resaco,0,'c109_inscricaopassiva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3489,19630,'','".AddSlashes(pg_result($resaco,0,'c109_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c109_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update conlancaminscricaoslip set ";
     $virgula = "";
     if(trim($this->c109_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c109_sequencial"])){ 
       $sql  .= $virgula." c109_sequencial = $this->c109_sequencial ";
       $virgula = ",";
       if(trim($this->c109_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "c109_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c109_codlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c109_codlan"])){ 
       $sql  .= $virgula." c109_codlan = $this->c109_codlan ";
       $virgula = ",";
       if(trim($this->c109_codlan) == null ){ 
         $this->erro_sql = " Campo C�digo Lan�amento nao Informado.";
         $this->erro_campo = "c109_codlan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c109_inscricaopassiva)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c109_inscricaopassiva"])){ 
       $sql  .= $virgula." c109_inscricaopassiva = $this->c109_inscricaopassiva ";
       $virgula = ",";
       if(trim($this->c109_inscricaopassiva) == null ){ 
         $this->erro_sql = " Campo Inscri��o Passiva nao Informado.";
         $this->erro_campo = "c109_inscricaopassiva";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c109_slip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c109_slip"])){ 
       $sql  .= $virgula." c109_slip = $this->c109_slip ";
       $virgula = ",";
       if(trim($this->c109_slip) == null ){ 
         $this->erro_sql = " Campo C�digo Slip nao Informado.";
         $this->erro_campo = "c109_slip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c109_sequencial!=null){
       $sql .= " c109_sequencial = $this->c109_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c109_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19627,'$this->c109_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c109_sequencial"]) || $this->c109_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3489,19627,'".AddSlashes(pg_result($resaco,$conresaco,'c109_sequencial'))."','$this->c109_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c109_codlan"]) || $this->c109_codlan != "")
           $resac = db_query("insert into db_acount values($acount,3489,19628,'".AddSlashes(pg_result($resaco,$conresaco,'c109_codlan'))."','$this->c109_codlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c109_inscricaopassiva"]) || $this->c109_inscricaopassiva != "")
           $resac = db_query("insert into db_acount values($acount,3489,19629,'".AddSlashes(pg_result($resaco,$conresaco,'c109_inscricaopassiva'))."','$this->c109_inscricaopassiva',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c109_slip"]) || $this->c109_slip != "")
           $resac = db_query("insert into db_acount values($acount,3489,19630,'".AddSlashes(pg_result($resaco,$conresaco,'c109_slip'))."','$this->c109_slip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Slips inscri��o nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c109_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Slips inscri��o nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c109_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c109_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c109_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c109_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19627,'$c109_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3489,19627,'','".AddSlashes(pg_result($resaco,$iresaco,'c109_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3489,19628,'','".AddSlashes(pg_result($resaco,$iresaco,'c109_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3489,19629,'','".AddSlashes(pg_result($resaco,$iresaco,'c109_inscricaopassiva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3489,19630,'','".AddSlashes(pg_result($resaco,$iresaco,'c109_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conlancaminscricaoslip
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c109_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c109_sequencial = $c109_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Slips inscri��o nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c109_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Slips inscri��o nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c109_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c109_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:conlancaminscricaoslip";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c109_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancaminscricaoslip ";
     $sql .= "      inner join slip  on  slip.k17_codigo = conlancaminscricaoslip.c109_slip";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancaminscricaoslip.c109_codlan";
     $sql .= "      inner join inscricaopassivo  on  inscricaopassivo.c36_sequencial = conlancaminscricaoslip.c109_inscricaopassiva";
     $sql .= "      inner join db_config  on  db_config.codigo = slip.k17_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = inscricaopassivo.c36_cgm";
     $sql .= "      inner join db_config  as a on   a.codigo = inscricaopassivo.c36_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inscricaopassivo.c36_db_usuarios";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = inscricaopassivo.c36_codele and  orcelemento.o56_anousu = inscricaopassivo.c36_anousu";
     $sql .= "      inner join conhist  on  conhist.c50_codhist = inscricaopassivo.c36_conhist";
     $sql2 = "";
     if($dbwhere==""){
       if($c109_sequencial!=null ){
         $sql2 .= " where conlancaminscricaoslip.c109_sequencial = $c109_sequencial "; 
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
   function sql_query_file ( $c109_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancaminscricaoslip ";
     $sql2 = "";
     if($dbwhere==""){
       if($c109_sequencial!=null ){
         $sql2 .= " where conlancaminscricaoslip.c109_sequencial = $c109_sequencial "; 
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