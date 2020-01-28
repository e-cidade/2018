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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancaminventario
class cl_conlancaminventario { 
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
   var $c85_sequencial = 0; 
   var $c85_codlan = 0; 
   var $c85_escriturainventario = 0; 
   var $c85_reduz = 0; 
   var $c85_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c85_sequencial = int4 = Sequencia lançamento inventario 
                 c85_codlan = int4 = Lançamento 
                 c85_escriturainventario = int4 = Escritura Inventário 
                 c85_reduz = int4 = Reduzido 
                 c85_anousu = int4 = Exercício 
                 ";
   //funcao construtor da classe 
   function cl_conlancaminventario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancaminventario"); 
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
       $this->c85_sequencial = ($this->c85_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c85_sequencial"]:$this->c85_sequencial);
       $this->c85_codlan = ($this->c85_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c85_codlan"]:$this->c85_codlan);
       $this->c85_escriturainventario = ($this->c85_escriturainventario == ""?@$GLOBALS["HTTP_POST_VARS"]["c85_escriturainventario"]:$this->c85_escriturainventario);
       $this->c85_reduz = ($this->c85_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c85_reduz"]:$this->c85_reduz);
       $this->c85_anousu = ($this->c85_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c85_anousu"]:$this->c85_anousu);
     }else{
       $this->c85_sequencial = ($this->c85_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c85_sequencial"]:$this->c85_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c85_sequencial){ 
      $this->atualizacampos();
     if($this->c85_codlan == null ){ 
       $this->erro_sql = " Campo Lançamento nao Informado.";
       $this->erro_campo = "c85_codlan";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c85_escriturainventario == null ){ 
       $this->erro_sql = " Campo Escritura Inventário nao Informado.";
       $this->erro_campo = "c85_escriturainventario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c85_reduz == null ){ 
       $this->erro_sql = " Campo Reduzido nao Informado.";
       $this->erro_campo = "c85_reduz";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c85_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "c85_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c85_sequencial == "" || $c85_sequencial == null ){
       $result = db_query("select nextval('conlancaminventario_c85_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conlancaminventario_c85_sequencial_seq do campo: c85_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c85_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from conlancaminventario_c85_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c85_sequencial)){
         $this->erro_sql = " Campo c85_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c85_sequencial = $c85_sequencial; 
       }
     }
     if(($this->c85_sequencial == null) || ($this->c85_sequencial == "") ){ 
       $this->erro_sql = " Campo c85_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     
     $sql = "insert into conlancaminventario(
                                       c85_sequencial 
                                      ,c85_codlan 
                                      ,c85_escriturainventario 
                                      ,c85_reduz 
                                      ,c85_anousu 
                       )
                values (
                                $this->c85_sequencial 
                               ,$this->c85_codlan 
                               ,$this->c85_escriturainventario 
                               ,$this->c85_reduz 
                               ,$this->c85_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lançamento inventário ($this->c85_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lançamento inventário já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lançamento inventário ($this->c85_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c85_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c85_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19450,'$this->c85_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3452,19450,'','".AddSlashes(pg_result($resaco,0,'c85_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3452,19451,'','".AddSlashes(pg_result($resaco,0,'c85_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3452,19452,'','".AddSlashes(pg_result($resaco,0,'c85_escriturainventario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3452,19509,'','".AddSlashes(pg_result($resaco,0,'c85_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3452,19510,'','".AddSlashes(pg_result($resaco,0,'c85_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c85_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update conlancaminventario set ";
     $virgula = "";
     if(trim($this->c85_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c85_sequencial"])){ 
       $sql  .= $virgula." c85_sequencial = $this->c85_sequencial ";
       $virgula = ",";
       if(trim($this->c85_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencia lançamento inventario nao Informado.";
         $this->erro_campo = "c85_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c85_codlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c85_codlan"])){ 
       $sql  .= $virgula." c85_codlan = $this->c85_codlan ";
       $virgula = ",";
       if(trim($this->c85_codlan) == null ){ 
         $this->erro_sql = " Campo Lançamento nao Informado.";
         $this->erro_campo = "c85_codlan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c85_escriturainventario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c85_escriturainventario"])){ 
       $sql  .= $virgula." c85_escriturainventario = $this->c85_escriturainventario ";
       $virgula = ",";
       if(trim($this->c85_escriturainventario) == null ){ 
         $this->erro_sql = " Campo Escritura Inventário nao Informado.";
         $this->erro_campo = "c85_escriturainventario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c85_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c85_reduz"])){ 
       $sql  .= $virgula." c85_reduz = $this->c85_reduz ";
       $virgula = ",";
       if(trim($this->c85_reduz) == null ){ 
         $this->erro_sql = " Campo Reduzido nao Informado.";
         $this->erro_campo = "c85_reduz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c85_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c85_anousu"])){ 
       $sql  .= $virgula." c85_anousu = $this->c85_anousu ";
       $virgula = ",";
       if(trim($this->c85_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "c85_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c85_sequencial!=null){
       $sql .= " c85_sequencial = $this->c85_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c85_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19450,'$this->c85_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c85_sequencial"]) || $this->c85_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3452,19450,'".AddSlashes(pg_result($resaco,$conresaco,'c85_sequencial'))."','$this->c85_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c85_codlan"]) || $this->c85_codlan != "")
           $resac = db_query("insert into db_acount values($acount,3452,19451,'".AddSlashes(pg_result($resaco,$conresaco,'c85_codlan'))."','$this->c85_codlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c85_escriturainventario"]) || $this->c85_escriturainventario != "")
           $resac = db_query("insert into db_acount values($acount,3452,19452,'".AddSlashes(pg_result($resaco,$conresaco,'c85_escriturainventario'))."','$this->c85_escriturainventario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c85_reduz"]) || $this->c85_reduz != "")
           $resac = db_query("insert into db_acount values($acount,3452,19509,'".AddSlashes(pg_result($resaco,$conresaco,'c85_reduz'))."','$this->c85_reduz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c85_anousu"]) || $this->c85_anousu != "")
           $resac = db_query("insert into db_acount values($acount,3452,19510,'".AddSlashes(pg_result($resaco,$conresaco,'c85_anousu'))."','$this->c85_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamento inventário nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c85_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamento inventário nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c85_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c85_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19450,'$c85_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3452,19450,'','".AddSlashes(pg_result($resaco,$iresaco,'c85_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3452,19451,'','".AddSlashes(pg_result($resaco,$iresaco,'c85_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3452,19452,'','".AddSlashes(pg_result($resaco,$iresaco,'c85_escriturainventario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3452,19509,'','".AddSlashes(pg_result($resaco,$iresaco,'c85_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3452,19510,'','".AddSlashes(pg_result($resaco,$iresaco,'c85_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conlancaminventario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c85_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c85_sequencial = $c85_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamento inventário nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c85_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamento inventário nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c85_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conlancaminventario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c85_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancaminventario ";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancaminventario.c85_codlan";
     $sql .= "      inner join conplanoreduz  on  conplanoreduz.c61_reduz = conlancaminventario.c85_reduz and  conplanoreduz.c61_anousu = conlancaminventario.c85_anousu";
     $sql .= "      inner join escriturainventario  on  escriturainventario.c88_sequencial = conlancaminventario.c85_escriturainventario";
     $sql .= "      inner join db_config  on  db_config.codigo = conplanoreduz.c61_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = conplanoreduz.c61_codigo";
     $sql .= "      inner join conplano  as a on   a.c60_codcon = conplanoreduz.c61_codcon and   a.c60_anousu = conplanoreduz.c61_anousu";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = escriturainventario.c88_usuario";
     $sql .= "      inner join conhistdoc  on  conhistdoc.c53_coddoc = escriturainventario.c88_documentoanterior";
     $sql .= "      inner join inventario  on  inventario.t75_sequencial = escriturainventario.c88_inventario";
     $sql2 = "";
     if($dbwhere==""){
       if($c85_sequencial!=null ){
         $sql2 .= " where conlancaminventario.c85_sequencial = $c85_sequencial "; 
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
   function sql_query_file ( $c85_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancaminventario ";
     $sql2 = "";
     if($dbwhere==""){
       if($c85_sequencial!=null ){
         $sql2 .= " where conlancaminventario.c85_sequencial = $c85_sequencial "; 
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