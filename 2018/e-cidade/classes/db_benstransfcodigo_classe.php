<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: patrimonio
//CLASSE DA ENTIDADE benstransfcodigo
class cl_benstransfcodigo { 
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
   var $t95_codtran = 0; 
   var $t95_codbem = 0; 
   var $t95_situac = 0; 
   var $t95_histor = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t95_codtran = int8 = Transferência 
                 t95_codbem = int8 = Código do bem 
                 t95_situac = int8 = Código da situação 
                 t95_histor = text = Histórico 
                 ";
   //funcao construtor da classe 
   function cl_benstransfcodigo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("benstransfcodigo"); 
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
       $this->t95_codtran = ($this->t95_codtran == ""?@$GLOBALS["HTTP_POST_VARS"]["t95_codtran"]:$this->t95_codtran);
       $this->t95_codbem = ($this->t95_codbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t95_codbem"]:$this->t95_codbem);
       $this->t95_situac = ($this->t95_situac == ""?@$GLOBALS["HTTP_POST_VARS"]["t95_situac"]:$this->t95_situac);
       $this->t95_histor = ($this->t95_histor == ""?@$GLOBALS["HTTP_POST_VARS"]["t95_histor"]:$this->t95_histor);
     }else{
       $this->t95_codtran = ($this->t95_codtran == ""?@$GLOBALS["HTTP_POST_VARS"]["t95_codtran"]:$this->t95_codtran);
       $this->t95_codbem = ($this->t95_codbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t95_codbem"]:$this->t95_codbem);
     }
   }
   // funcao para inclusao
   function incluir ($t95_codtran,$t95_codbem){ 
      $this->atualizacampos();
     if($this->t95_situac == null ){ 
       $this->erro_sql = " Campo Código da situação nao Informado.";
       $this->erro_campo = "t95_situac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->t95_codtran = $t95_codtran; 
       $this->t95_codbem = $t95_codbem; 
     if(($this->t95_codtran == null) || ($this->t95_codtran == "") ){ 
       $this->erro_sql = " Campo t95_codtran nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->t95_codbem == null) || ($this->t95_codbem == "") ){ 
       $this->erro_sql = " Campo t95_codbem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into benstransfcodigo(
                                       t95_codtran 
                                      ,t95_codbem 
                                      ,t95_situac 
                                      ,t95_histor 
                       )
                values (
                                $this->t95_codtran 
                               ,$this->t95_codbem 
                               ,$this->t95_situac 
                               ,'$this->t95_histor' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens tranferencias ($this->t95_codtran."-".$this->t95_codbem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens tranferencias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens tranferencias ($this->t95_codtran."-".$this->t95_codbem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t95_codtran."-".$this->t95_codbem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t95_codtran,$this->t95_codbem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5830,'$this->t95_codtran','I')");
       $resac = db_query("insert into db_acountkey values($acount,5831,'$this->t95_codbem','I')");
       $resac = db_query("insert into db_acount values($acount,943,5830,'','".AddSlashes(pg_result($resaco,0,'t95_codtran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,943,5831,'','".AddSlashes(pg_result($resaco,0,'t95_codbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,943,5981,'','".AddSlashes(pg_result($resaco,0,'t95_situac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,943,5982,'','".AddSlashes(pg_result($resaco,0,'t95_histor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t95_codtran=null,$t95_codbem=null) { 
      $this->atualizacampos();
     $sql = " update benstransfcodigo set ";
     $virgula = "";
     if(trim($this->t95_codtran)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t95_codtran"])){ 
       $sql  .= $virgula." t95_codtran = $this->t95_codtran ";
       $virgula = ",";
       if(trim($this->t95_codtran) == null ){ 
         $this->erro_sql = " Campo Transferência nao Informado.";
         $this->erro_campo = "t95_codtran";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t95_codbem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t95_codbem"])){ 
       $sql  .= $virgula." t95_codbem = $this->t95_codbem ";
       $virgula = ",";
       if(trim($this->t95_codbem) == null ){ 
         $this->erro_sql = " Campo Código do bem nao Informado.";
         $this->erro_campo = "t95_codbem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t95_situac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t95_situac"])){ 
       $sql  .= $virgula." t95_situac = $this->t95_situac ";
       $virgula = ",";
       if(trim($this->t95_situac) == null ){ 
         $this->erro_sql = " Campo Código da situação nao Informado.";
         $this->erro_campo = "t95_situac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t95_histor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t95_histor"])){ 
       $sql  .= $virgula." t95_histor = '$this->t95_histor' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($t95_codtran!=null){
       $sql .= " t95_codtran = $this->t95_codtran";
     }
     if($t95_codbem!=null){
       $sql .= " and  t95_codbem = $this->t95_codbem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t95_codtran,$this->t95_codbem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5830,'$this->t95_codtran','A')");
         $resac = db_query("insert into db_acountkey values($acount,5831,'$this->t95_codbem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t95_codtran"]) || $this->t95_codtran != "")
           $resac = db_query("insert into db_acount values($acount,943,5830,'".AddSlashes(pg_result($resaco,$conresaco,'t95_codtran'))."','$this->t95_codtran',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t95_codbem"]) || $this->t95_codbem != "")
           $resac = db_query("insert into db_acount values($acount,943,5831,'".AddSlashes(pg_result($resaco,$conresaco,'t95_codbem'))."','$this->t95_codbem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t95_situac"]) || $this->t95_situac != "")
           $resac = db_query("insert into db_acount values($acount,943,5981,'".AddSlashes(pg_result($resaco,$conresaco,'t95_situac'))."','$this->t95_situac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t95_histor"]) || $this->t95_histor != "")
           $resac = db_query("insert into db_acount values($acount,943,5982,'".AddSlashes(pg_result($resaco,$conresaco,'t95_histor'))."','$this->t95_histor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens tranferencias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t95_codtran."-".$this->t95_codbem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens tranferencias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t95_codtran."-".$this->t95_codbem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t95_codtran."-".$this->t95_codbem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t95_codtran=null,$t95_codbem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t95_codtran,$t95_codbem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5830,'$t95_codtran','E')");
         $resac = db_query("insert into db_acountkey values($acount,5831,'$t95_codbem','E')");
         $resac = db_query("insert into db_acount values($acount,943,5830,'','".AddSlashes(pg_result($resaco,$iresaco,'t95_codtran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,943,5831,'','".AddSlashes(pg_result($resaco,$iresaco,'t95_codbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,943,5981,'','".AddSlashes(pg_result($resaco,$iresaco,'t95_situac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,943,5982,'','".AddSlashes(pg_result($resaco,$iresaco,'t95_histor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from benstransfcodigo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t95_codtran != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t95_codtran = $t95_codtran ";
        }
        if($t95_codbem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t95_codbem = $t95_codbem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens tranferencias nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t95_codtran."-".$t95_codbem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens tranferencias nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t95_codtran."-".$t95_codbem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t95_codtran."-".$t95_codbem;
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
        $this->erro_sql   = "Record Vazio na Tabela:benstransfcodigo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t95_codtran=null,$t95_codbem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benstransfcodigo ";
     $sql .= "      inner join bens  on  bens.t52_bem = benstransfcodigo.t95_codbem";
     $sql .= "      inner join situabens  on  situabens.t70_situac = benstransfcodigo.t95_situac";
     $sql .= "      inner join benstransf  on  benstransf.t93_codtran = benstransfcodigo.t95_codtran";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = bens.t52_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      inner join bensmarca  on  bensmarca.t65_sequencial = bens.t52_bensmarca";
     $sql .= "      inner join bensmodelo  on  bensmodelo.t66_sequencial = bens.t52_bensmodelo";
     $sql .= "      inner join bensmedida  on  bensmedida.t67_sequencial = bens.t52_bensmedida";
     $sql .= "      inner join db_config  as a on   a.codigo = benstransf.t93_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = benstransf.t93_id_usuario";
     $sql .= "      inner join db_depart  as b on   b.coddepto = benstransf.t93_depart";
     $sql2 = "";
     if($dbwhere==""){
       if($t95_codtran!=null ){
         $sql2 .= " where benstransfcodigo.t95_codtran = $t95_codtran "; 
       } 
       if($t95_codbem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " benstransfcodigo.t95_codbem = $t95_codbem "; 
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
   function sql_query_file ( $t95_codtran=null,$t95_codbem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benstransfcodigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($t95_codtran!=null ){
         $sql2 .= " where benstransfcodigo.t95_codtran = $t95_codtran "; 
       } 
       if($t95_codbem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " benstransfcodigo.t95_codbem = $t95_codbem "; 
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
   function sql_query_benstransf ( $t95_codtran=null,$t95_codbem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from benstransfcodigo ";
     $sql .= "      inner join bens  on  bens.t52_bem = benstransfcodigo.t95_codbem";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql2 = "";
     if($dbwhere==""){
       if($t95_codtran!=null ){
         $sql2 .= " where benstransfcodigo.t95_codtran = $t95_codtran ";
       }
       if($t95_codbem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " benstransfcodigo.t95_codbem = $t95_codbem ";
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
   function sql_query_benstransf_origdestsitua ( $t95_codtran = null, $t95_codbem = null,$campos = "*", $ordem = null, $dbwhere = "" ){
    
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
     $sql .= " from benstransfcodigo ";
     $sql .= "      inner join bens              on bens.t52_bem              = benstransfcodigo.t95_codbem"; 
     $sql .= "      inner join clabens           on clabens.t64_codcla        = bens.t52_codcla";
     $sql .= "      inner join benstransf        on benstransf.t93_codtran    = benstransfcodigo.t95_codtran"; 
     $sql .= "      left  join benstransfdiv     on benstransfdiv.t31_bem     = bens.t52_bem";
     $sql .= "                                  and benstransfdiv.t31_codtran = benstransf.t93_codtran";
     $sql .= "      left  join bensdiv           on bensdiv.t33_bem           = bens.t52_bem"; 
     $sql .= "      left  join departdiv origem  on origem.t30_codigo         = bensdiv.t33_divisao";
     $sql .= "      left  join departdiv destino on destino.t30_codigo        = benstransfdiv.t31_divisao"; 
     $sql .= "      inner join situabens         on situabens.t70_situac      = benstransfcodigo.t95_situac";
     $sql .= "      left  join benstransforigemdestino on benstransfcodigo.t95_codtran = benstransforigemdestino.t34_transferencia";
     $sql .= "                                        and benstransfcodigo.t95_codbem  = benstransforigemdestino.t34_bem ";                      
     $sql .= "       left join departdiv divisaoorigem  on  divisaoorigem.t30_codigo  = benstransforigemdestino.t34_divisaoorigem ";
     $sql .= "       left join departdiv divisaodestino on  divisaodestino.t30_codigo = benstransforigemdestino.t34_divisaodestino ";
     
     
     $sql2 = "";
     if($dbwhere==""){
       if($t95_codtran!=null ){
         $sql2 .= " where benstransfcodigo.t95_codtran = $t95_codtran ";
       }
       if($t95_codbem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " benstransfcodigo.t95_codbem = $t95_codbem ";
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
   function sql_query_div ( $t95_codtran=null,$t95_codbem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from benstransfcodigo ";     
     $sql .= "      inner join bens  on  bens.t52_bem = benstransfcodigo.t95_codbem";
     $sql .= "      inner join situabens  on  situabens.t70_situac = benstransfcodigo.t95_situac";
     $sql .= "      inner join benstransf  on  benstransf.t93_codtran = benstransfcodigo.t95_codtran";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = benstransf.t93_id_usuario";
     $sql .= "      inner join db_depart  as a on   a.coddepto = benstransf.t93_depart";     
     $sql .= "      left  join benstransfdiv on t31_codtran = t95_codtran and t31_bem = t95_codbem";
     $sql .= "      left  join departdiv on t31_divisao = t30_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($t95_codtran!=null ){
         $sql2 .= " where benstransfcodigo.t95_codtran = $t95_codtran ";
       }
       if($t95_codbem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " benstransfcodigo.t95_codbem = $t95_codbem ";
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
