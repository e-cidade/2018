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

//MODULO: vacinas
//CLASSE DA ENTIDADE vac_vacinamaterial
class cl_vac_vacinamaterial { 
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
   var $vc29_i_dose = 0; 
   var $vc29_i_codigo = 0; 
   var $vc29_i_vacina = 0; 
   var $vc29_i_material = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc29_i_dose = int4 = Dose 
                 vc29_i_codigo = int4 = Codigo 
                 vc29_i_vacina = int4 = Vacina 
                 vc29_i_material = int4 = Material 
                 ";
   //funcao construtor da classe 
   function cl_vac_vacinamaterial() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_vacinamaterial"); 
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
       $this->vc29_i_dose = ($this->vc29_i_dose == ""?@$GLOBALS["HTTP_POST_VARS"]["vc29_i_dose"]:$this->vc29_i_dose);
       $this->vc29_i_codigo = ($this->vc29_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc29_i_codigo"]:$this->vc29_i_codigo);
       $this->vc29_i_vacina = ($this->vc29_i_vacina == ""?@$GLOBALS["HTTP_POST_VARS"]["vc29_i_vacina"]:$this->vc29_i_vacina);
       $this->vc29_i_material = ($this->vc29_i_material == ""?@$GLOBALS["HTTP_POST_VARS"]["vc29_i_material"]:$this->vc29_i_material);
     }else{
       $this->vc29_i_codigo = ($this->vc29_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc29_i_codigo"]:$this->vc29_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc29_i_codigo){ 
      $this->atualizacampos();
     if($this->vc29_i_dose == null ){ 
       $this->erro_sql = " Campo Dose nao Informado.";
       $this->erro_campo = "vc29_i_dose";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc29_i_vacina == null ){ 
       $this->erro_sql = " Campo Vacina nao Informado.";
       $this->erro_campo = "vc29_i_vacina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc29_i_material == null ){ 
       $this->erro_sql = " Campo Material nao Informado.";
       $this->erro_campo = "vc29_i_material";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($vc29_i_codigo == "" || $vc29_i_codigo == null ){
       $result = db_query("select nextval('vac_vacinamaterial_vc29_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_vacinamaterial_vc29_i_codigo_seq do campo: vc29_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc29_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_vacinamaterial_vc29_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc29_i_codigo)){
         $this->erro_sql = " Campo vc29_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc29_i_codigo = $vc29_i_codigo; 
       }
     }
     if(($this->vc29_i_codigo == null) || ($this->vc29_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc29_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_vacinamaterial(
                                       vc29_i_dose 
                                      ,vc29_i_codigo 
                                      ,vc29_i_vacina 
                                      ,vc29_i_material 
                       )
                values (
                                $this->vc29_i_dose 
                               ,$this->vc29_i_codigo 
                               ,$this->vc29_i_vacina 
                               ,$this->vc29_i_material 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vacina material ($this->vc29_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vacina material já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vacina material ($this->vc29_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc29_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc29_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17933,'$this->vc29_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3170,17936,'','".AddSlashes(pg_result($resaco,0,'vc29_i_dose'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3170,17933,'','".AddSlashes(pg_result($resaco,0,'vc29_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3170,17934,'','".AddSlashes(pg_result($resaco,0,'vc29_i_vacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3170,17935,'','".AddSlashes(pg_result($resaco,0,'vc29_i_material'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc29_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_vacinamaterial set ";
     $virgula = "";
     if(trim($this->vc29_i_dose)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc29_i_dose"])){ 
       $sql  .= $virgula." vc29_i_dose = $this->vc29_i_dose ";
       $virgula = ",";
       if(trim($this->vc29_i_dose) == null ){ 
         $this->erro_sql = " Campo Dose nao Informado.";
         $this->erro_campo = "vc29_i_dose";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc29_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc29_i_codigo"])){ 
       $sql  .= $virgula." vc29_i_codigo = $this->vc29_i_codigo ";
       $virgula = ",";
       if(trim($this->vc29_i_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "vc29_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc29_i_vacina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc29_i_vacina"])){ 
       $sql  .= $virgula." vc29_i_vacina = $this->vc29_i_vacina ";
       $virgula = ",";
       if(trim($this->vc29_i_vacina) == null ){ 
         $this->erro_sql = " Campo Vacina nao Informado.";
         $this->erro_campo = "vc29_i_vacina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc29_i_material)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc29_i_material"])){ 
       $sql  .= $virgula." vc29_i_material = $this->vc29_i_material ";
       $virgula = ",";
       if(trim($this->vc29_i_material) == null ){ 
         $this->erro_sql = " Campo Material nao Informado.";
         $this->erro_campo = "vc29_i_material";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($vc29_i_codigo!=null){
       $sql .= " vc29_i_codigo = $this->vc29_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc29_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17933,'$this->vc29_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc29_i_dose"]) || $this->vc29_i_dose != "")
           $resac = db_query("insert into db_acount values($acount,3170,17936,'".AddSlashes(pg_result($resaco,$conresaco,'vc29_i_dose'))."','$this->vc29_i_dose',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc29_i_codigo"]) || $this->vc29_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3170,17933,'".AddSlashes(pg_result($resaco,$conresaco,'vc29_i_codigo'))."','$this->vc29_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc29_i_vacina"]) || $this->vc29_i_vacina != "")
           $resac = db_query("insert into db_acount values($acount,3170,17934,'".AddSlashes(pg_result($resaco,$conresaco,'vc29_i_vacina'))."','$this->vc29_i_vacina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc29_i_material"]) || $this->vc29_i_material != "")
           $resac = db_query("insert into db_acount values($acount,3170,17935,'".AddSlashes(pg_result($resaco,$conresaco,'vc29_i_material'))."','$this->vc29_i_material',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vacina material nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc29_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vacina material nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc29_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc29_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc29_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc29_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17933,'$vc29_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3170,17936,'','".AddSlashes(pg_result($resaco,$iresaco,'vc29_i_dose'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3170,17933,'','".AddSlashes(pg_result($resaco,$iresaco,'vc29_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3170,17934,'','".AddSlashes(pg_result($resaco,$iresaco,'vc29_i_vacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3170,17935,'','".AddSlashes(pg_result($resaco,$iresaco,'vc29_i_material'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_vacinamaterial
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc29_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc29_i_codigo = $vc29_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vacina material nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc29_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vacina material nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc29_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc29_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_vacinamaterial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_vacinamaterial ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = vac_vacinamaterial.vc29_i_material";
     $sql .= "      inner join vac_vacina  on  vac_vacina.vc06_i_codigo = vac_vacinamaterial.vc29_i_vacina";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql .= "      inner join vac_tipovacina  on  vac_tipovacina.vc04_i_codigo = vac_vacina.vc06_i_tipovacina";
     $sql2 = "";
     if($dbwhere==""){
       if($vc29_i_codigo!=null ){
         $sql2 .= " where vac_vacinamaterial.vc29_i_codigo = $vc29_i_codigo "; 
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
   function sql_query_file ( $vc29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_vacinamaterial ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc29_i_codigo!=null ){
         $sql2 .= " where vac_vacinamaterial.vc29_i_codigo = $vc29_i_codigo "; 
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
  function sql_query_vacina ( $m77_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemlote ";
     $sql .= "      inner join matestoqueitem     on  matestoqueitem.m71_codlanc = matestoqueitemlote.m77_matestoqueitem";
     $sql .= "      inner join matestoque         on  matestoque.m70_codigo      = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join matmater           on  matmater.m60_codmater      = matestoque.m70_codmatmater";
     $sql .= "      inner join matunid            on  matunid.m61_codmatunid     = matmater.m60_codmatunid ";
     $sql .= "      inner join vac_vacinamaterial on  vac_vacinamaterial.vc29_i_material = matestoque.m70_codmatmater";
     $sql .= "      inner join vac_vacina         on  vac_vacina.vc06_i_codigo   = vac_vacinamaterial.vc29_i_vacina";
     $sql2 = "";
     if($dbwhere==""){
       if($m77_sequencial!=null ){
         $sql2 .= " where matestoqueitemlote.m77_sequencial = $m77_sequencial "; 
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