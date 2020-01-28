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

//MODULO: pessoal
//CLASSE DA ENTIDADE efetiv
class cl_efetiv { 
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
   var $r57_instit = 0; 
   var $r57_anousu = 0; 
   var $r57_mesusu = 0; 
   var $r57_codrel = null; 
   var $r57_regist = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r57_instit = int4 = Cod. Instituição 
                 r57_anousu = int4 = Ano do Exercicio 
                 r57_mesusu = int4 = Mes do Exercicio 
                 r57_codrel = varchar(4) = Código do convênio 
                 r57_regist = int4 = Codigo do Funcionario 
                 ";
   //funcao construtor da classe 
   function cl_efetiv() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("efetiv"); 
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
       $this->r57_instit = ($this->r57_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r57_instit"]:$this->r57_instit);
       $this->r57_anousu = ($this->r57_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r57_anousu"]:$this->r57_anousu);
       $this->r57_mesusu = ($this->r57_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r57_mesusu"]:$this->r57_mesusu);
       $this->r57_codrel = ($this->r57_codrel == ""?@$GLOBALS["HTTP_POST_VARS"]["r57_codrel"]:$this->r57_codrel);
       $this->r57_regist = ($this->r57_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r57_regist"]:$this->r57_regist);
     }else{
       $this->r57_instit = ($this->r57_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r57_instit"]:$this->r57_instit);
       $this->r57_anousu = ($this->r57_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r57_anousu"]:$this->r57_anousu);
       $this->r57_mesusu = ($this->r57_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r57_mesusu"]:$this->r57_mesusu);
       $this->r57_codrel = ($this->r57_codrel == ""?@$GLOBALS["HTTP_POST_VARS"]["r57_codrel"]:$this->r57_codrel);
       $this->r57_regist = ($this->r57_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r57_regist"]:$this->r57_regist);
     }
   }
   // funcao para inclusao
   function incluir ($r57_anousu,$r57_mesusu,$r57_codrel,$r57_regist,$r57_instit){ 
      $this->atualizacampos();
       $this->r57_anousu = $r57_anousu; 
       $this->r57_mesusu = $r57_mesusu; 
       $this->r57_codrel = $r57_codrel; 
       $this->r57_regist = $r57_regist; 
       $this->r57_instit = $r57_instit; 
     if(($this->r57_anousu == null) || ($this->r57_anousu == "") ){ 
       $this->erro_sql = " Campo r57_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r57_mesusu == null) || ($this->r57_mesusu == "") ){ 
       $this->erro_sql = " Campo r57_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r57_codrel == null) || ($this->r57_codrel == "") ){ 
       $this->erro_sql = " Campo r57_codrel nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r57_regist == null) || ($this->r57_regist == "") ){ 
       $this->erro_sql = " Campo r57_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r57_instit == null) || ($this->r57_instit == "") ){ 
       $this->erro_sql = " Campo r57_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into efetiv(
                                       r57_instit 
                                      ,r57_anousu 
                                      ,r57_mesusu 
                                      ,r57_codrel 
                                      ,r57_regist 
                       )
                values (
                                $this->r57_instit 
                               ,$this->r57_anousu 
                               ,$this->r57_mesusu 
                               ,'$this->r57_codrel' 
                               ,$this->r57_regist 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Efetividade do funcionario                         ($this->r57_anousu."-".$this->r57_mesusu."-".$this->r57_codrel."-".$this->r57_regist."-".$this->r57_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Efetividade do funcionario                         já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Efetividade do funcionario                         ($this->r57_anousu."-".$this->r57_mesusu."-".$this->r57_codrel."-".$this->r57_regist."-".$this->r57_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r57_anousu."-".$this->r57_mesusu."-".$this->r57_codrel."-".$this->r57_regist."-".$this->r57_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r57_anousu,$this->r57_mesusu,$this->r57_codrel,$this->r57_regist,$this->r57_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3885,'$this->r57_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3886,'$this->r57_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3887,'$this->r57_codrel','I')");
       $resac = db_query("insert into db_acountkey values($acount,3888,'$this->r57_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,9914,'$this->r57_instit','I')");
       $resac = db_query("insert into db_acount values($acount,546,9914,'','".AddSlashes(pg_result($resaco,0,'r57_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,546,3885,'','".AddSlashes(pg_result($resaco,0,'r57_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,546,3886,'','".AddSlashes(pg_result($resaco,0,'r57_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,546,3887,'','".AddSlashes(pg_result($resaco,0,'r57_codrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,546,3888,'','".AddSlashes(pg_result($resaco,0,'r57_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r57_anousu=null,$r57_mesusu=null,$r57_codrel=null,$r57_regist=null,$r57_instit=null) { 
      $this->atualizacampos();
     $sql = " update efetiv set ";
     $virgula = "";
     if(trim($this->r57_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r57_instit"])){ 
       $sql  .= $virgula." r57_instit = $this->r57_instit ";
       $virgula = ",";
       if(trim($this->r57_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "r57_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r57_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r57_anousu"])){ 
       $sql  .= $virgula." r57_anousu = $this->r57_anousu ";
       $virgula = ",";
       if(trim($this->r57_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r57_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r57_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r57_mesusu"])){ 
       $sql  .= $virgula." r57_mesusu = $this->r57_mesusu ";
       $virgula = ",";
       if(trim($this->r57_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r57_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r57_codrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r57_codrel"])){ 
       $sql  .= $virgula." r57_codrel = '$this->r57_codrel' ";
       $virgula = ",";
       if(trim($this->r57_codrel) == null ){ 
         $this->erro_sql = " Campo Código do convênio nao Informado.";
         $this->erro_campo = "r57_codrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r57_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r57_regist"])){ 
       $sql  .= $virgula." r57_regist = $this->r57_regist ";
       $virgula = ",";
       if(trim($this->r57_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r57_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r57_anousu!=null){
       $sql .= " r57_anousu = $this->r57_anousu";
     }
     if($r57_mesusu!=null){
       $sql .= " and  r57_mesusu = $this->r57_mesusu";
     }
     if($r57_codrel!=null){
       $sql .= " and  r57_codrel = '$this->r57_codrel'";
     }
     if($r57_regist!=null){
       $sql .= " and  r57_regist = $this->r57_regist";
     }
     if($r57_instit!=null){
       $sql .= " and  r57_instit = $this->r57_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r57_anousu,$this->r57_mesusu,$this->r57_codrel,$this->r57_regist,$this->r57_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3885,'$this->r57_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3886,'$this->r57_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3887,'$this->r57_codrel','A')");
         $resac = db_query("insert into db_acountkey values($acount,3888,'$this->r57_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,9914,'$this->r57_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r57_instit"]))
           $resac = db_query("insert into db_acount values($acount,546,9914,'".AddSlashes(pg_result($resaco,$conresaco,'r57_instit'))."','$this->r57_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r57_anousu"]))
           $resac = db_query("insert into db_acount values($acount,546,3885,'".AddSlashes(pg_result($resaco,$conresaco,'r57_anousu'))."','$this->r57_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r57_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,546,3886,'".AddSlashes(pg_result($resaco,$conresaco,'r57_mesusu'))."','$this->r57_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r57_codrel"]))
           $resac = db_query("insert into db_acount values($acount,546,3887,'".AddSlashes(pg_result($resaco,$conresaco,'r57_codrel'))."','$this->r57_codrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r57_regist"]))
           $resac = db_query("insert into db_acount values($acount,546,3888,'".AddSlashes(pg_result($resaco,$conresaco,'r57_regist'))."','$this->r57_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Efetividade do funcionario                         nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r57_anousu."-".$this->r57_mesusu."-".$this->r57_codrel."-".$this->r57_regist."-".$this->r57_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Efetividade do funcionario                         nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r57_anousu."-".$this->r57_mesusu."-".$this->r57_codrel."-".$this->r57_regist."-".$this->r57_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r57_anousu."-".$this->r57_mesusu."-".$this->r57_codrel."-".$this->r57_regist."-".$this->r57_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r57_anousu=null,$r57_mesusu=null,$r57_codrel=null,$r57_regist=null,$r57_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r57_anousu,$r57_mesusu,$r57_codrel,$r57_regist,$r57_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3885,'$r57_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3886,'$r57_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3887,'$r57_codrel','E')");
         $resac = db_query("insert into db_acountkey values($acount,3888,'$r57_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,9914,'$r57_instit','E')");
         $resac = db_query("insert into db_acount values($acount,546,9914,'','".AddSlashes(pg_result($resaco,$iresaco,'r57_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,546,3885,'','".AddSlashes(pg_result($resaco,$iresaco,'r57_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,546,3886,'','".AddSlashes(pg_result($resaco,$iresaco,'r57_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,546,3887,'','".AddSlashes(pg_result($resaco,$iresaco,'r57_codrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,546,3888,'','".AddSlashes(pg_result($resaco,$iresaco,'r57_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from efetiv
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r57_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r57_anousu = $r57_anousu ";
        }
        if($r57_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r57_mesusu = $r57_mesusu ";
        }
        if($r57_codrel != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r57_codrel = '$r57_codrel' ";
        }
        if($r57_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r57_regist = $r57_regist ";
        }
        if($r57_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r57_instit = $r57_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Efetividade do funcionario                         nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r57_anousu."-".$r57_mesusu."-".$r57_codrel."-".$r57_regist."-".$r57_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Efetividade do funcionario                         nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r57_anousu."-".$r57_mesusu."-".$r57_codrel."-".$r57_regist."-".$r57_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r57_anousu."-".$r57_mesusu."-".$r57_codrel."-".$r57_regist."-".$r57_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:efetiv";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_incluir (){
  	 $this->incluir($this->r57_anousu,$this->r57_mesusu,$this->r57_codrel,$this->regist);
   }
   function sql_query ( $r57_anousu=null,$r57_mesusu=null,$r57_codrel=null,$r57_regist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from efetiv ";
     $sql .= "      inner join convenio  on  convenio.r56_codrel = efetiv.r57_codrel";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = efetiv.r57_anousu and  pessoal.r01_mesusu = efetiv.r57_mesusu and  pessoal.r01_regist = efetiv.r57_regist";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  on  funcao.r37_anousu = pessoal.r01_anousu and  funcao.r37_mesusu = pessoal.r01_mesusu and  funcao.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join inssirf  on  inssirf.r33_anousu = pessoal.r01_anousu and  inssirf.r33_mesusu = pessoal.r01_mesusu and  inssirf.r33_codtab = pessoal.r01_tbprev";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pessoal.r01_anousu and  lotacao.r13_mesusu = pessoal.r01_mesusu and  lotacao.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  on  cargo.r65_anousu = pessoal.r01_anousu and  cargo.r65_mesusu = pessoal.r01_mesusu and  cargo.r65_cargo = pessoal.r01_cargo";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  as b on   b.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  as c on   c.r37_anousu = pessoal.r01_anousu and   c.r37_mesusu = pessoal.r01_mesusu and   c.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join inssirf  as d on   d.r33_anousu = pessoal.r01_anousu and   d.r33_mesusu = pessoal.r01_mesusu and   d.r33_codtab = pessoal.r01_tbprev";
     $sql .= "      inner join lotacao  as d on   d.r13_anousu = pessoal.r01_anousu and   d.r13_mesusu = pessoal.r01_mesusu and   d.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  as d on   d.r65_anousu = pessoal.r01_anousu and   d.r65_mesusu = pessoal.r01_mesusu and   d.r65_cargo = pessoal.r01_cargo";
     $sql .= "      inner join cgm  as d on   d.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  as d on   d.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  as d on   d.r37_anousu = pessoal.r01_anousu and   d.r37_mesusu = pessoal.r01_mesusu and   d.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join inssirf  as d on   d.r33_anousu = pessoal.r01_anousu and   d.r33_mesusu = pessoal.r01_mesusu and   d.r33_codtab = pessoal.r01_tbprev";
     $sql .= "      inner join lotacao  as d on   d.r13_anousu = pessoal.r01_anousu and   d.r13_mesusu = pessoal.r01_mesusu and   d.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  as d on   d.r65_anousu = pessoal.r01_anousu and   d.r65_mesusu = pessoal.r01_mesusu and   d.r65_cargo = pessoal.r01_cargo";
     $sql2 = "";
     if($dbwhere==""){
       if($r57_anousu!=null ){
         $sql2 .= " where efetiv.r57_anousu = $r57_anousu "; 
       } 
       if($r57_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " efetiv.r57_mesusu = $r57_mesusu "; 
       } 
       if($r57_codrel!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " efetiv.r57_codrel = '$r57_codrel' "; 
       } 
       if($r57_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " efetiv.r57_regist = $r57_regist "; 
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
   function sql_query_file ( $r57_anousu=null,$r57_mesusu=null,$r57_codrel=null,$r57_regist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from efetiv ";
     $sql2 = "";
     if($dbwhere==""){
       if($r57_anousu!=null ){
         $sql2 .= " where efetiv.r57_anousu = $r57_anousu "; 
       } 
       if($r57_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " efetiv.r57_mesusu = $r57_mesusu "; 
       } 
       if($r57_codrel!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " efetiv.r57_codrel = '$r57_codrel' "; 
       } 
       if($r57_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " efetiv.r57_regist = $r57_regist "; 
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