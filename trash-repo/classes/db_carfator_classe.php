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

//MODULO: Cadastro
//CLASSE DA ENTIDADE carfator
class cl_carfator { 
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
   var $j74_anousu = 0; 
   var $j74_caract = 0; 
   var $j74_fator = 0; 
   var $j74_corrig = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j74_anousu = int4 = Ano 
                 j74_caract = int8 = Caracteristica 
                 j74_fator = float8 = Fator 
                 j74_corrig = bool = Corrigir 
                 ";
   //funcao construtor da classe 
   function cl_carfator() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("carfator"); 
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
       $this->j74_anousu = ($this->j74_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j74_anousu"]:$this->j74_anousu);
       $this->j74_caract = ($this->j74_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["j74_caract"]:$this->j74_caract);
       $this->j74_fator = ($this->j74_fator == ""?@$GLOBALS["HTTP_POST_VARS"]["j74_fator"]:$this->j74_fator);
       $this->j74_corrig = ($this->j74_corrig == "f"?@$GLOBALS["HTTP_POST_VARS"]["j74_corrig"]:$this->j74_corrig);
     }else{
       $this->j74_anousu = ($this->j74_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j74_anousu"]:$this->j74_anousu);
       $this->j74_caract = ($this->j74_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["j74_caract"]:$this->j74_caract);
     }
   }
   // funcao para inclusao
   function incluir ($j74_anousu,$j74_caract){ 
      $this->atualizacampos();
     if($this->j74_fator == null ){ 
       $this->erro_sql = " Campo Fator nao Informado.";
       $this->erro_campo = "j74_fator";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j74_corrig == null ){ 
       $this->erro_sql = " Campo Corrigir nao Informado.";
       $this->erro_campo = "j74_corrig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j74_anousu = $j74_anousu; 
       $this->j74_caract = $j74_caract; 
     if(($this->j74_anousu == null) || ($this->j74_anousu == "") ){ 
       $this->erro_sql = " Campo j74_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j74_caract == null) || ($this->j74_caract == "") ){ 
       $this->erro_sql = " Campo j74_caract nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into carfator(
                                       j74_anousu 
                                      ,j74_caract 
                                      ,j74_fator 
                                      ,j74_corrig 
                       )
                values (
                                $this->j74_anousu 
                               ,$this->j74_caract 
                               ,$this->j74_fator 
                               ,'$this->j74_corrig' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fator por caracteristica ($this->j74_anousu."-".$this->j74_caract) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fator por caracteristica já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fator por caracteristica ($this->j74_anousu."-".$this->j74_caract) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j74_anousu."-".$this->j74_caract;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j74_anousu,$this->j74_caract));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7677,'$this->j74_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,7678,'$this->j74_caract','I')");
       $resac = db_query("insert into db_acount values($acount,1274,7677,'','".AddSlashes(pg_result($resaco,0,'j74_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1274,7678,'','".AddSlashes(pg_result($resaco,0,'j74_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1274,7679,'','".AddSlashes(pg_result($resaco,0,'j74_fator'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1274,7680,'','".AddSlashes(pg_result($resaco,0,'j74_corrig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j74_anousu=null,$j74_caract=null) { 
      $this->atualizacampos();
     $sql = " update carfator set ";
     $virgula = "";
     if(trim($this->j74_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j74_anousu"])){ 
       $sql  .= $virgula." j74_anousu = $this->j74_anousu ";
       $virgula = ",";
       if(trim($this->j74_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "j74_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j74_caract)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j74_caract"])){ 
       $sql  .= $virgula." j74_caract = $this->j74_caract ";
       $virgula = ",";
       if(trim($this->j74_caract) == null ){ 
         $this->erro_sql = " Campo Caracteristica nao Informado.";
         $this->erro_campo = "j74_caract";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j74_fator)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j74_fator"])){ 
       $sql  .= $virgula." j74_fator = $this->j74_fator ";
       $virgula = ",";
       if(trim($this->j74_fator) == null ){ 
         $this->erro_sql = " Campo Fator nao Informado.";
         $this->erro_campo = "j74_fator";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j74_corrig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j74_corrig"])){ 
       $sql  .= $virgula." j74_corrig = '$this->j74_corrig' ";
       $virgula = ",";
       if(trim($this->j74_corrig) == null ){ 
         $this->erro_sql = " Campo Corrigir nao Informado.";
         $this->erro_campo = "j74_corrig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j74_anousu!=null){
       $sql .= " j74_anousu = $this->j74_anousu";
     }
     if($j74_caract!=null){
       $sql .= " and  j74_caract = $this->j74_caract";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j74_anousu,$this->j74_caract));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7677,'$this->j74_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,7678,'$this->j74_caract','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j74_anousu"]) || $this->j74_anousu != "")
           $resac = db_query("insert into db_acount values($acount,1274,7677,'".AddSlashes(pg_result($resaco,$conresaco,'j74_anousu'))."','$this->j74_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j74_caract"]) || $this->j74_caract != "")
           $resac = db_query("insert into db_acount values($acount,1274,7678,'".AddSlashes(pg_result($resaco,$conresaco,'j74_caract'))."','$this->j74_caract',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j74_fator"]) || $this->j74_fator != "")
           $resac = db_query("insert into db_acount values($acount,1274,7679,'".AddSlashes(pg_result($resaco,$conresaco,'j74_fator'))."','$this->j74_fator',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j74_corrig"]) || $this->j74_corrig != "")
           $resac = db_query("insert into db_acount values($acount,1274,7680,'".AddSlashes(pg_result($resaco,$conresaco,'j74_corrig'))."','$this->j74_corrig',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fator por caracteristica nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j74_anousu."-".$this->j74_caract;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fator por caracteristica nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j74_anousu."-".$this->j74_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j74_anousu."-".$this->j74_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j74_anousu=null,$j74_caract=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j74_anousu,$j74_caract));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7677,'$j74_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,7678,'$j74_caract','E')");
         $resac = db_query("insert into db_acount values($acount,1274,7677,'','".AddSlashes(pg_result($resaco,$iresaco,'j74_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1274,7678,'','".AddSlashes(pg_result($resaco,$iresaco,'j74_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1274,7679,'','".AddSlashes(pg_result($resaco,$iresaco,'j74_fator'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1274,7680,'','".AddSlashes(pg_result($resaco,$iresaco,'j74_corrig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from carfator
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j74_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j74_anousu = $j74_anousu ";
        }
        if($j74_caract != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j74_caract = $j74_caract ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fator por caracteristica nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j74_anousu."-".$j74_caract;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fator por caracteristica nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j74_anousu."-".$j74_caract;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j74_anousu."-".$j74_caract;
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
        $this->erro_sql   = "Record Vazio na Tabela:carfator";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j74_anousu=null,$j74_caract=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carfator ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = carfator.j74_caract";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql2 = "";
     if($dbwhere==""){
       if($j74_anousu!=null ){
         $sql2 .= " where carfator.j74_anousu = $j74_anousu "; 
       } 
       if($j74_caract!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " carfator.j74_caract = $j74_caract "; 
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
   function sql_query_file ( $j74_anousu=null,$j74_caract=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carfator ";
     $sql2 = "";
     if($dbwhere==""){
       if($j74_anousu!=null ){
         $sql2 .= " where carfator.j74_anousu = $j74_anousu "; 
       } 
       if($j74_caract!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " carfator.j74_caract = $j74_caract "; 
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