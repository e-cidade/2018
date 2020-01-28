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

//MODULO: licita��o
//CLASSE DA ENTIDADE pccflicitapar
class cl_pccflicitapar { 
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
   var $l25_codigo = 0; 
   var $l25_codcflicita = 0; 
   var $l25_anousu = 0; 
   var $l25_numero = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l25_codigo = int4 = Cod. Seq. 
                 l25_codcflicita = int4 = Codigo Sequencial 
                 l25_anousu = int4 = Ano 
                 l25_numero = int8 = Numera��o 
                 ";
   //funcao construtor da classe 
   function cl_pccflicitapar() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pccflicitapar"); 
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
       $this->l25_codigo = ($this->l25_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l25_codigo"]:$this->l25_codigo);
       $this->l25_codcflicita = ($this->l25_codcflicita == ""?@$GLOBALS["HTTP_POST_VARS"]["l25_codcflicita"]:$this->l25_codcflicita);
       $this->l25_anousu = ($this->l25_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["l25_anousu"]:$this->l25_anousu);
       $this->l25_numero = ($this->l25_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["l25_numero"]:$this->l25_numero);
     }else{
       $this->l25_codigo = ($this->l25_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l25_codigo"]:$this->l25_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($l25_codigo){ 
      $this->atualizacampos();
     if($this->l25_codcflicita == null ){ 
       $this->erro_sql = " Campo Codigo Sequencial nao Informado.";
       $this->erro_campo = "l25_codcflicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l25_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "l25_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l25_numero == null ){ 
       $this->erro_sql = " Campo Numera��o nao Informado.";
       $this->erro_campo = "l25_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l25_codigo == "" || $l25_codigo == null ){
       $result = db_query("select nextval('pccflicitapar_l25_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pccflicitapar_l25_codigo_seq do campo: l25_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l25_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pccflicitapar_l25_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $l25_codigo)){
         $this->erro_sql = " Campo l25_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l25_codigo = $l25_codigo; 
       }
     }
     if(($this->l25_codigo == null) || ($this->l25_codigo == "") ){ 
       $this->erro_sql = " Campo l25_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pccflicitapar(
                                       l25_codigo 
                                      ,l25_codcflicita 
                                      ,l25_anousu 
                                      ,l25_numero 
                       )
                values (
                                $this->l25_codigo 
                               ,$this->l25_codcflicita 
                               ,$this->l25_anousu 
                               ,$this->l25_numero 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "pccflicitapar ($this->l25_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "pccflicitapar j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "pccflicitapar ($this->l25_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l25_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l25_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7766,'$this->l25_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1272,7766,'','".AddSlashes(pg_result($resaco,0,'l25_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1272,7667,'','".AddSlashes(pg_result($resaco,0,'l25_codcflicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1272,7668,'','".AddSlashes(pg_result($resaco,0,'l25_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1272,7669,'','".AddSlashes(pg_result($resaco,0,'l25_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l25_codigo=null) { 
      $this->atualizacampos();
     $sql = " update pccflicitapar set ";
     $virgula = "";
     if(trim($this->l25_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l25_codigo"])){ 
       $sql  .= $virgula." l25_codigo = $this->l25_codigo ";
       $virgula = ",";
       if(trim($this->l25_codigo) == null ){ 
         $this->erro_sql = " Campo Cod. Seq. nao Informado.";
         $this->erro_campo = "l25_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l25_codcflicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l25_codcflicita"])){ 
       $sql  .= $virgula." l25_codcflicita = $this->l25_codcflicita ";
       $virgula = ",";
       if(trim($this->l25_codcflicita) == null ){ 
         $this->erro_sql = " Campo Codigo Sequencial nao Informado.";
         $this->erro_campo = "l25_codcflicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l25_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l25_anousu"])){ 
       $sql  .= $virgula." l25_anousu = $this->l25_anousu ";
       $virgula = ",";
       if(trim($this->l25_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "l25_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l25_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l25_numero"])){ 
       $sql  .= $virgula." l25_numero = $this->l25_numero ";
       $virgula = ",";
       if(trim($this->l25_numero) == null ){ 
         $this->erro_sql = " Campo Numera��o nao Informado.";
         $this->erro_campo = "l25_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l25_codigo!=null){
       $sql .= " l25_codigo = $this->l25_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l25_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7766,'$this->l25_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l25_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1272,7766,'".AddSlashes(pg_result($resaco,$conresaco,'l25_codigo'))."','$this->l25_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l25_codcflicita"]))
           $resac = db_query("insert into db_acount values($acount,1272,7667,'".AddSlashes(pg_result($resaco,$conresaco,'l25_codcflicita'))."','$this->l25_codcflicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l25_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1272,7668,'".AddSlashes(pg_result($resaco,$conresaco,'l25_anousu'))."','$this->l25_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l25_numero"]))
           $resac = db_query("insert into db_acount values($acount,1272,7669,'".AddSlashes(pg_result($resaco,$conresaco,'l25_numero'))."','$this->l25_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pccflicitapar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l25_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pccflicitapar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l25_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l25_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l25_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l25_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7766,'$l25_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1272,7766,'','".AddSlashes(pg_result($resaco,$iresaco,'l25_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1272,7667,'','".AddSlashes(pg_result($resaco,$iresaco,'l25_codcflicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1272,7668,'','".AddSlashes(pg_result($resaco,$iresaco,'l25_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1272,7669,'','".AddSlashes(pg_result($resaco,$iresaco,'l25_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pccflicitapar
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l25_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l25_codigo = $l25_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pccflicitapar nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l25_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pccflicitapar nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l25_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l25_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:pccflicitapar";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function alterar_where ($l25_codigo=null,$dbwhere="") { 
      $this->atualizacampos();
     $sql = " update pccflicitapar set ";
     $virgula = "";
     if(trim($this->l25_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l25_codigo"])){ 
       $sql  .= $virgula." l25_codigo = $this->l25_codigo ";
       $virgula = ",";
       if(trim($this->l25_codigo) == null ){ 
         $this->erro_sql = " Campo Cod. Seq. nao Informado.";
         $this->erro_campo = "l25_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l25_codcflicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l25_codcflicita"])){ 
       $sql  .= $virgula." l25_codcflicita = $this->l25_codcflicita ";
       $virgula = ",";
       if(trim($this->l25_codcflicita) == null ){ 
         $this->erro_sql = " Campo Codigo Sequencial nao Informado.";
         $this->erro_campo = "l25_codcflicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l25_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l25_anousu"])){ 
       $sql  .= $virgula." l25_anousu = $this->l25_anousu ";
       $virgula = ",";
       if(trim($this->l25_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "l25_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l25_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l25_numero"])){ 
       $sql  .= $virgula." l25_numero = $this->l25_numero ";
       $virgula = ",";
       if(trim($this->l25_numero) == null ){ 
         $this->erro_sql = " Campo Numera��o nao Informado.";
         $this->erro_campo = "l25_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if($dbwhere==""){
       $sql .= " where ";
       if($l25_codigo!=null){
         $sql .= " l25_codigo = $this->l25_codigo";
       }
     }else if($dbwhere != ""){
       $sql .= " where $dbwhere";
     }
     
     $resaco = $this->sql_record($this->sql_query_file($this->l25_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,7766,'$this->l25_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l25_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1272,7766,'".AddSlashes(pg_result($resaco,$conresaco,'l25_codigo'))."','$this->l25_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l25_codcflicita"]))
           $resac = pg_query("insert into db_acount values($acount,1272,7667,'".AddSlashes(pg_result($resaco,$conresaco,'l25_codcflicita'))."','$this->l25_codcflicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l25_anousu"]))
           $resac = pg_query("insert into db_acount values($acount,1272,7668,'".AddSlashes(pg_result($resaco,$conresaco,'l25_anousu'))."','$this->l25_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l25_numero"]))
           $resac = pg_query("insert into db_acount values($acount,1272,7669,'".AddSlashes(pg_result($resaco,$conresaco,'l25_numero'))."','$this->l25_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pccflicitapar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l25_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pccflicitapar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l25_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l25_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   }
   function sql_query ( $l25_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pccflicitapar ";
     $sql .= "      inner join cflicita  on  cflicita.l03_codigo = pccflicitapar.l25_codcflicita";
     $sql .= "      inner join db_config  on  db_config.codigo = cflicita.l03_instit";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = cflicita.l03_codcom";
     $sql2 = "";
     if($dbwhere==""){
       if($l25_codigo!=null ){
         $sql2 .= " where pccflicitapar.l25_codigo = $l25_codigo "; 
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
   function sql_query_file ( $l25_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pccflicitapar ";
     $sql2 = "";
     if($dbwhere==""){
       if($l25_codigo!=null ){
         $sql2 .= " where pccflicitapar.l25_codigo = $l25_codigo "; 
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

function sql_query_modalidade ( $l25_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pccflicitapar ";
     $sql .= " inner join cflicita on cflicita.l03_codigo=pccflicitapar.l25_codcflicita ";
     $sql2 = "";
     if($dbwhere==""){
       if($l25_codigo!=null ){
         $sql2 .= " where pccflicitapar.l25_codigo = $l25_codigo ";
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

function sql_query_mod_licita( $l25_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pccflicitapar ";
     $sql .= " inner join liclicita on pccflicitapar.l25_codcflicita=liclicita.l20_codtipocom ";
     $sql2 = "";
     if($dbwhere==""){
       if($l25_codigo!=null ){
         $sql2 .= " where pccflicitapar.l25_codigo = $l25_codigo ";
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