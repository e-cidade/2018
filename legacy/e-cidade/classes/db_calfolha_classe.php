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
//CLASSE DA ENTIDADE calfolha
class cl_calfolha { 
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
   var $r51_anousu = 0; 
   var $r51_mesusu = 0; 
   var $r51_regist = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r51_anousu = int4 = Ano do Exercicio 
                 r51_mesusu = int4 = Mes do Exercicio 
                 r51_regist = int4 = Codigo do Funcionario 
                 ";
   //funcao construtor da classe 
   function cl_calfolha() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("calfolha"); 
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
       $this->r51_anousu = ($this->r51_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r51_anousu"]:$this->r51_anousu);
       $this->r51_mesusu = ($this->r51_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r51_mesusu"]:$this->r51_mesusu);
       $this->r51_regist = ($this->r51_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r51_regist"]:$this->r51_regist);
     }else{
       $this->r51_anousu = ($this->r51_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r51_anousu"]:$this->r51_anousu);
       $this->r51_mesusu = ($this->r51_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r51_mesusu"]:$this->r51_mesusu);
       $this->r51_regist = ($this->r51_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r51_regist"]:$this->r51_regist);
     }
   }
   // funcao para inclusao
   function incluir ($r51_anousu,$r51_mesusu,$r51_regist){ 
      $this->atualizacampos();
       $this->r51_anousu = $r51_anousu; 
       $this->r51_mesusu = $r51_mesusu; 
       $this->r51_regist = $r51_regist; 
     if(($this->r51_anousu == null) || ($this->r51_anousu == "") ){ 
       $this->erro_sql = " Campo r51_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r51_mesusu == null) || ($this->r51_mesusu == "") ){ 
       $this->erro_sql = " Campo r51_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r51_regist == null) || ($this->r51_regist == "") ){ 
       $this->erro_sql = " Campo r51_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into calfolha(
                                       r51_anousu 
                                      ,r51_mesusu 
                                      ,r51_regist 
                       )
                values (
                                $this->r51_anousu 
                               ,$this->r51_mesusu 
                               ,$this->r51_regist 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "arq.auxiliar do calculo da folha                   ($this->r51_anousu."-".$this->r51_mesusu."-".$this->r51_regist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "arq.auxiliar do calculo da folha                   já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "arq.auxiliar do calculo da folha                   ($this->r51_anousu."-".$this->r51_mesusu."-".$this->r51_regist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r51_anousu."-".$this->r51_mesusu."-".$this->r51_regist;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r51_anousu,$this->r51_mesusu,$this->r51_regist));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3743,'$this->r51_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3744,'$this->r51_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3745,'$this->r51_regist','I')");
       $resac = db_query("insert into db_acount values($acount,534,3743,'','".AddSlashes(pg_result($resaco,0,'r51_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,534,3744,'','".AddSlashes(pg_result($resaco,0,'r51_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,534,3745,'','".AddSlashes(pg_result($resaco,0,'r51_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r51_anousu=null,$r51_mesusu=null,$r51_regist=null) { 
      $this->atualizacampos();
     $sql = " update calfolha set ";
     $virgula = "";
     if(trim($this->r51_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r51_anousu"])){ 
       $sql  .= $virgula." r51_anousu = $this->r51_anousu ";
       $virgula = ",";
       if(trim($this->r51_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r51_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r51_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r51_mesusu"])){ 
       $sql  .= $virgula." r51_mesusu = $this->r51_mesusu ";
       $virgula = ",";
       if(trim($this->r51_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r51_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r51_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r51_regist"])){ 
       $sql  .= $virgula." r51_regist = $this->r51_regist ";
       $virgula = ",";
       if(trim($this->r51_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r51_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r51_anousu!=null){
       $sql .= " r51_anousu = $this->r51_anousu";
     }
     if($r51_mesusu!=null){
       $sql .= " and  r51_mesusu = $this->r51_mesusu";
     }
     if($r51_regist!=null){
       $sql .= " and  r51_regist = $this->r51_regist";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r51_anousu,$this->r51_mesusu,$this->r51_regist));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3743,'$this->r51_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3744,'$this->r51_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3745,'$this->r51_regist','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r51_anousu"]))
           $resac = db_query("insert into db_acount values($acount,534,3743,'".AddSlashes(pg_result($resaco,$conresaco,'r51_anousu'))."','$this->r51_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r51_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,534,3744,'".AddSlashes(pg_result($resaco,$conresaco,'r51_mesusu'))."','$this->r51_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r51_regist"]))
           $resac = db_query("insert into db_acount values($acount,534,3745,'".AddSlashes(pg_result($resaco,$conresaco,'r51_regist'))."','$this->r51_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arq.auxiliar do calculo da folha                   nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r51_anousu."-".$this->r51_mesusu."-".$this->r51_regist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arq.auxiliar do calculo da folha                   nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r51_anousu."-".$this->r51_mesusu."-".$this->r51_regist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r51_anousu."-".$this->r51_mesusu."-".$this->r51_regist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r51_anousu=null,$r51_mesusu=null,$r51_regist=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r51_anousu,$r51_mesusu,$r51_regist));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3743,'$r51_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3744,'$r51_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3745,'$r51_regist','E')");
         $resac = db_query("insert into db_acount values($acount,534,3743,'','".AddSlashes(pg_result($resaco,$iresaco,'r51_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,534,3744,'','".AddSlashes(pg_result($resaco,$iresaco,'r51_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,534,3745,'','".AddSlashes(pg_result($resaco,$iresaco,'r51_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from calfolha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r51_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r51_anousu = $r51_anousu ";
        }
        if($r51_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r51_mesusu = $r51_mesusu ";
        }
        if($r51_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r51_regist = $r51_regist ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arq.auxiliar do calculo da folha                   nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r51_anousu."-".$r51_mesusu."-".$r51_regist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arq.auxiliar do calculo da folha                   nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r51_anousu."-".$r51_mesusu."-".$r51_regist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r51_anousu."-".$r51_mesusu."-".$r51_regist;
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
        $this->erro_sql   = "Record Vazio na Tabela:calfolha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>