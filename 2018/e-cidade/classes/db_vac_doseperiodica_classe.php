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

//MODULO: Vacinas
//CLASSE DA ENTIDADE vac_doseperiodica
class cl_vac_doseperiodica { 
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
   var $vc14_i_codigo = 0; 
   var $vc14_i_vacinadose = 0; 
   var $vc14_i_faixadia = 0; 
   var $vc14_i_faixames = 0; 
   var $vc14_i_faixaano = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc14_i_codigo = int4 = Código 
                 vc14_i_vacinadose = int4 = Vacina 
                 vc14_i_faixadia = int4 = Faixa dias 
                 vc14_i_faixames = int4 = Faixa mes 
                 vc14_i_faixaano = int4 = Faixa ano 
                 ";
   //funcao construtor da classe 
   function cl_vac_doseperiodica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_doseperiodica"); 
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
       $this->vc14_i_codigo = ($this->vc14_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc14_i_codigo"]:$this->vc14_i_codigo);
       $this->vc14_i_vacinadose = ($this->vc14_i_vacinadose == ""?@$GLOBALS["HTTP_POST_VARS"]["vc14_i_vacinadose"]:$this->vc14_i_vacinadose);
       $this->vc14_i_faixadia = ($this->vc14_i_faixadia == ""?@$GLOBALS["HTTP_POST_VARS"]["vc14_i_faixadia"]:$this->vc14_i_faixadia);
       $this->vc14_i_faixames = ($this->vc14_i_faixames == ""?@$GLOBALS["HTTP_POST_VARS"]["vc14_i_faixames"]:$this->vc14_i_faixames);
       $this->vc14_i_faixaano = ($this->vc14_i_faixaano == ""?@$GLOBALS["HTTP_POST_VARS"]["vc14_i_faixaano"]:$this->vc14_i_faixaano);
     }else{
       $this->vc14_i_codigo = ($this->vc14_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc14_i_codigo"]:$this->vc14_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc14_i_codigo){ 
      $this->atualizacampos();
     if($this->vc14_i_vacinadose == null ){ 
       $this->erro_sql = " Campo Vacina nao Informado.";
       $this->erro_campo = "vc14_i_vacinadose";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc14_i_faixadia == null ){ 
       $this->erro_sql = " Campo Faixa dias nao Informado.";
       $this->erro_campo = "vc14_i_faixadia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc14_i_faixames == null ){ 
       $this->erro_sql = " Campo Faixa mes nao Informado.";
       $this->erro_campo = "vc14_i_faixames";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc14_i_faixaano == null ){ 
       $this->erro_sql = " Campo Faixa ano nao Informado.";
       $this->erro_campo = "vc14_i_faixaano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($vc14_i_codigo == "" || $vc14_i_codigo == null ){
       $result = db_query("select nextval('vac_doseperiodica_vc14_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_doseperiodica_vc14_i_codigo_seq do campo: vc14_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc14_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_doseperiodica_vc14_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc14_i_codigo)){
         $this->erro_sql = " Campo vc14_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc14_i_codigo = $vc14_i_codigo; 
       }
     }
     if(($this->vc14_i_codigo == null) || ($this->vc14_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc14_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_doseperiodica(
                                       vc14_i_codigo 
                                      ,vc14_i_vacinadose 
                                      ,vc14_i_faixadia 
                                      ,vc14_i_faixames 
                                      ,vc14_i_faixaano 
                       )
                values (
                                $this->vc14_i_codigo 
                               ,$this->vc14_i_vacinadose 
                               ,$this->vc14_i_faixadia 
                               ,$this->vc14_i_faixames 
                               ,$this->vc14_i_faixaano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dose periodica ($this->vc14_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dose periodica já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dose periodica ($this->vc14_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc14_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc14_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16862,'$this->vc14_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2968,16862,'','".AddSlashes(pg_result($resaco,0,'vc14_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2968,16863,'','".AddSlashes(pg_result($resaco,0,'vc14_i_vacinadose'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2968,16864,'','".AddSlashes(pg_result($resaco,0,'vc14_i_faixadia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2968,16865,'','".AddSlashes(pg_result($resaco,0,'vc14_i_faixames'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2968,16866,'','".AddSlashes(pg_result($resaco,0,'vc14_i_faixaano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc14_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_doseperiodica set ";
     $virgula = "";
     if(trim($this->vc14_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc14_i_codigo"])){ 
       $sql  .= $virgula." vc14_i_codigo = $this->vc14_i_codigo ";
       $virgula = ",";
       if(trim($this->vc14_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "vc14_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc14_i_vacinadose)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc14_i_vacinadose"])){ 
       $sql  .= $virgula." vc14_i_vacinadose = $this->vc14_i_vacinadose ";
       $virgula = ",";
       if(trim($this->vc14_i_vacinadose) == null ){ 
         $this->erro_sql = " Campo Vacina nao Informado.";
         $this->erro_campo = "vc14_i_vacinadose";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc14_i_faixadia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc14_i_faixadia"])){ 
       $sql  .= $virgula." vc14_i_faixadia = $this->vc14_i_faixadia ";
       $virgula = ",";
       if(trim($this->vc14_i_faixadia) == null ){ 
         $this->erro_sql = " Campo Faixa dias nao Informado.";
         $this->erro_campo = "vc14_i_faixadia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc14_i_faixames)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc14_i_faixames"])){ 
       $sql  .= $virgula." vc14_i_faixames = $this->vc14_i_faixames ";
       $virgula = ",";
       if(trim($this->vc14_i_faixames) == null ){ 
         $this->erro_sql = " Campo Faixa mes nao Informado.";
         $this->erro_campo = "vc14_i_faixames";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc14_i_faixaano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc14_i_faixaano"])){ 
       $sql  .= $virgula." vc14_i_faixaano = $this->vc14_i_faixaano ";
       $virgula = ",";
       if(trim($this->vc14_i_faixaano) == null ){ 
         $this->erro_sql = " Campo Faixa ano nao Informado.";
         $this->erro_campo = "vc14_i_faixaano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($vc14_i_codigo!=null){
       $sql .= " vc14_i_codigo = $this->vc14_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc14_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16862,'$this->vc14_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc14_i_codigo"]) || $this->vc14_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2968,16862,'".AddSlashes(pg_result($resaco,$conresaco,'vc14_i_codigo'))."','$this->vc14_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc14_i_vacinadose"]) || $this->vc14_i_vacinadose != "")
           $resac = db_query("insert into db_acount values($acount,2968,16863,'".AddSlashes(pg_result($resaco,$conresaco,'vc14_i_vacinadose'))."','$this->vc14_i_vacinadose',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc14_i_faixadia"]) || $this->vc14_i_faixadia != "")
           $resac = db_query("insert into db_acount values($acount,2968,16864,'".AddSlashes(pg_result($resaco,$conresaco,'vc14_i_faixadia'))."','$this->vc14_i_faixadia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc14_i_faixames"]) || $this->vc14_i_faixames != "")
           $resac = db_query("insert into db_acount values($acount,2968,16865,'".AddSlashes(pg_result($resaco,$conresaco,'vc14_i_faixames'))."','$this->vc14_i_faixames',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc14_i_faixaano"]) || $this->vc14_i_faixaano != "")
           $resac = db_query("insert into db_acount values($acount,2968,16866,'".AddSlashes(pg_result($resaco,$conresaco,'vc14_i_faixaano'))."','$this->vc14_i_faixaano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dose periodica nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc14_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dose periodica nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc14_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc14_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc14_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc14_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16862,'$vc14_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2968,16862,'','".AddSlashes(pg_result($resaco,$iresaco,'vc14_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2968,16863,'','".AddSlashes(pg_result($resaco,$iresaco,'vc14_i_vacinadose'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2968,16864,'','".AddSlashes(pg_result($resaco,$iresaco,'vc14_i_faixadia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2968,16865,'','".AddSlashes(pg_result($resaco,$iresaco,'vc14_i_faixames'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2968,16866,'','".AddSlashes(pg_result($resaco,$iresaco,'vc14_i_faixaano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_doseperiodica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc14_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc14_i_codigo = $vc14_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dose periodica nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc14_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dose periodica nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc14_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc14_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_doseperiodica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc14_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_doseperiodica ";
     $sql .= "      inner join vac_vacinadose  on  vac_vacinadose.vc07_i_codigo = vac_doseperiodica.vc14_i_vacinadose";
     $sql .= "      inner join vac_dose  on  vac_dose.vc03_i_codigo = vac_vacinadose.vc07_i_dose";
     $sql .= "      inner join vac_calendario  on  vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario";
     $sql .= "      inner join vac_vacina  on  vac_vacina.vc06_i_codigo = vac_vacinadose.vc07_i_vacina";
     $sql2 = "";
     if($dbwhere==""){
       if($vc14_i_codigo!=null ){
         $sql2 .= " where vac_doseperiodica.vc14_i_codigo = $vc14_i_codigo "; 
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
   function sql_query_file ( $vc14_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_doseperiodica ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc14_i_codigo!=null ){
         $sql2 .= " where vac_doseperiodica.vc14_i_codigo = $vc14_i_codigo "; 
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