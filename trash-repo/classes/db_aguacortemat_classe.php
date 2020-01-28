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

//MODULO: agua
//CLASSE DA ENTIDADE aguacortemat
class cl_aguacortemat { 
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
   var $x41_codcortemat = 0; 
   var $x41_matric = 0; 
   var $x41_codcorte = 0; 
   var $x41_dtprazo_dia = null; 
   var $x41_dtprazo_mes = null; 
   var $x41_dtprazo_ano = null; 
   var $x41_dtprazo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x41_codcortemat = int4 = Corte Matricula 
                 x41_matric = int4 = Matrícula 
                 x41_codcorte = int4 = Corte 
                 x41_dtprazo = date = Prazo Regularização 
                 ";
   //funcao construtor da classe 
   function cl_aguacortemat() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacortemat"); 
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
       $this->x41_codcortemat = ($this->x41_codcortemat == ""?@$GLOBALS["HTTP_POST_VARS"]["x41_codcortemat"]:$this->x41_codcortemat);
       $this->x41_matric = ($this->x41_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["x41_matric"]:$this->x41_matric);
       $this->x41_codcorte = ($this->x41_codcorte == ""?@$GLOBALS["HTTP_POST_VARS"]["x41_codcorte"]:$this->x41_codcorte);
       if($this->x41_dtprazo == ""){
         $this->x41_dtprazo_dia = ($this->x41_dtprazo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x41_dtprazo_dia"]:$this->x41_dtprazo_dia);
         $this->x41_dtprazo_mes = ($this->x41_dtprazo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x41_dtprazo_mes"]:$this->x41_dtprazo_mes);
         $this->x41_dtprazo_ano = ($this->x41_dtprazo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x41_dtprazo_ano"]:$this->x41_dtprazo_ano);
         if($this->x41_dtprazo_dia != ""){
            $this->x41_dtprazo = $this->x41_dtprazo_ano."-".$this->x41_dtprazo_mes."-".$this->x41_dtprazo_dia;
         }
       }
     }else{
       $this->x41_codcortemat = ($this->x41_codcortemat == ""?@$GLOBALS["HTTP_POST_VARS"]["x41_codcortemat"]:$this->x41_codcortemat);
       $this->x41_codcorte = ($this->x41_codcorte == ""?@$GLOBALS["HTTP_POST_VARS"]["x41_codcorte"]:$this->x41_codcorte);
     }
   }
   // funcao para inclusao
   function incluir ($x41_codcortemat){ 
      $this->atualizacampos();
     if($this->x41_matric == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "x41_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x41_dtprazo == null ){ 
       $this->erro_sql = " Campo Prazo Regularização nao Informado.";
       $this->erro_campo = "x41_dtprazo_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x41_codcortemat == "" || $x41_codcortemat == null ){
       $result = db_query("select nextval('aguacortemat_x41_codcortemat_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacortemat_x41_codcortemat_seq do campo: x41_codcortemat"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x41_codcortemat = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguacortemat_x41_codcortemat_seq");
       if(($result != false) && (pg_result($result,0,0) < $x41_codcortemat)){
         $this->erro_sql = " Campo x41_codcortemat maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x41_codcortemat = $x41_codcortemat; 
       }
     }
     if(($this->x41_codcortemat == null) || ($this->x41_codcortemat == "") ){ 
       $this->erro_sql = " Campo x41_codcortemat nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacortemat(
                                       x41_codcortemat 
                                      ,x41_matric 
                                      ,x41_codcorte 
                                      ,x41_dtprazo 
                       )
                values (
                                $this->x41_codcortemat 
                               ,$this->x41_matric 
                               ,$this->x41_codcorte 
                               ,".($this->x41_dtprazo == "null" || $this->x41_dtprazo == ""?"null":"'".$this->x41_dtprazo."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguacortemat ($this->x41_codcortemat) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguacortemat já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguacortemat ($this->x41_codcortemat) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x41_codcortemat;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x41_codcortemat));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8545,'$this->x41_codcortemat','I')");
       $resac = db_query("insert into db_acount values($acount,1454,8545,'','".AddSlashes(pg_result($resaco,0,'x41_codcortemat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1454,8546,'','".AddSlashes(pg_result($resaco,0,'x41_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1454,8547,'','".AddSlashes(pg_result($resaco,0,'x41_codcorte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1454,8548,'','".AddSlashes(pg_result($resaco,0,'x41_dtprazo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x41_codcortemat=null) { 
      $this->atualizacampos();
     $sql = " update aguacortemat set ";
     $virgula = "";
     if(trim($this->x41_codcortemat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x41_codcortemat"])){ 
       $sql  .= $virgula." x41_codcortemat = $this->x41_codcortemat ";
       $virgula = ",";
       if(trim($this->x41_codcortemat) == null ){ 
         $this->erro_sql = " Campo Corte Matricula nao Informado.";
         $this->erro_campo = "x41_codcortemat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x41_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x41_matric"])){ 
       $sql  .= $virgula." x41_matric = $this->x41_matric ";
       $virgula = ",";
       if(trim($this->x41_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "x41_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x41_codcorte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x41_codcorte"])){ 
       $sql  .= $virgula." x41_codcorte = $this->x41_codcorte ";
       $virgula = ",";
       if(trim($this->x41_codcorte) == null ){ 
         $this->erro_sql = " Campo Corte nao Informado.";
         $this->erro_campo = "x41_codcorte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x41_dtprazo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x41_dtprazo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x41_dtprazo_dia"] !="") ){ 
       $sql  .= $virgula." x41_dtprazo = '$this->x41_dtprazo' ";
       $virgula = ",";
       if(trim($this->x41_dtprazo) == null ){ 
         $this->erro_sql = " Campo Prazo Regularização nao Informado.";
         $this->erro_campo = "x41_dtprazo_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x41_dtprazo_dia"])){ 
         $sql  .= $virgula." x41_dtprazo = null ";
         $virgula = ",";
         if(trim($this->x41_dtprazo) == null ){ 
           $this->erro_sql = " Campo Prazo Regularização nao Informado.";
           $this->erro_campo = "x41_dtprazo_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($x41_codcortemat!=null){
       $sql .= " x41_codcortemat = $this->x41_codcortemat";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x41_codcortemat));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8545,'$this->x41_codcortemat','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x41_codcortemat"]))
           $resac = db_query("insert into db_acount values($acount,1454,8545,'".AddSlashes(pg_result($resaco,$conresaco,'x41_codcortemat'))."','$this->x41_codcortemat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x41_matric"]))
           $resac = db_query("insert into db_acount values($acount,1454,8546,'".AddSlashes(pg_result($resaco,$conresaco,'x41_matric'))."','$this->x41_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x41_codcorte"]))
           $resac = db_query("insert into db_acount values($acount,1454,8547,'".AddSlashes(pg_result($resaco,$conresaco,'x41_codcorte'))."','$this->x41_codcorte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x41_dtprazo"]))
           $resac = db_query("insert into db_acount values($acount,1454,8548,'".AddSlashes(pg_result($resaco,$conresaco,'x41_dtprazo'))."','$this->x41_dtprazo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacortemat nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x41_codcortemat;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacortemat nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x41_codcortemat;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x41_codcortemat;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x41_codcortemat=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x41_codcortemat));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8545,'$x41_codcortemat','E')");
         $resac = db_query("insert into db_acount values($acount,1454,8545,'','".AddSlashes(pg_result($resaco,$iresaco,'x41_codcortemat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1454,8546,'','".AddSlashes(pg_result($resaco,$iresaco,'x41_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1454,8547,'','".AddSlashes(pg_result($resaco,$iresaco,'x41_codcorte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1454,8548,'','".AddSlashes(pg_result($resaco,$iresaco,'x41_dtprazo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguacortemat
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x41_codcortemat != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x41_codcortemat = $x41_codcortemat ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacortemat nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x41_codcortemat;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacortemat nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x41_codcortemat;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x41_codcortemat;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguacortemat";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x41_codcortemat=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacortemat ";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguacortemat.x41_matric";
     $sql .= "      inner join aguacorte  on  aguacorte.x40_codcorte = aguacortemat.x41_codcorte";
     $sql .= "      inner join bairro  on  bairro.j13_codi = aguabase.x01_codbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguabase.x01_codrua";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = aguabase.x01_numcgm";
     $sql .= "      inner join ruas  as a on   a.j14_codigo = aguacorte.x40_rua";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = aguacorte.x40_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($x41_codcortemat!=null ){
         $sql2 .= " where aguacortemat.x41_codcortemat = $x41_codcortemat "; 
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
   function sql_query_file ( $x41_codcortemat=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacortemat ";
     $sql2 = "";
     if($dbwhere==""){
       if($x41_codcortemat!=null ){
         $sql2 .= " where aguacortemat.x41_codcortemat = $x41_codcortemat "; 
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
   function sql_query_finalizado( $corte, $matric ) {

	  $sql = "
		     select x43_regra 
		       from aguacortematmov
		            inner join aguacortemat on x41_codcortemat = x42_codcortemat
		            inner join aguacortesituacao on x43_codsituacao = x42_codsituacao
		      where x41_codcorte = $corte
            and x41_matric   = $matric
		   order by x42_data   desc, 
                x42_codmov desc
		      limit 1 ";
    //if($matric==35551) {
    //  die($sql);
    //}
    $result = pg_query($sql);

    if(pg_numrows($result)==0) {
      return false;
    }
   
    // Regras : 
    // 0 - Normal
    // 1 - Inicia Procedimento de Corte
    // 2 - Finaliza Procedimento de Corte
    // 3 - Bloqueia Corte
    $x43_regra = pg_result($result, 0, "x43_regra");

    //if($matric == 35551) {
    //  die("$sql <br> matric $matric regra $x43_regra");
    //}

    // Se regra = 2 (Finaliza Procedimento de Corte)
    // ou regra = 3 (Bloqueia Corte)
    if($x43_regra == 2 || $x43_regra == 3) {
      // Retorna que procedimento foi finalizado
      return true;
    } 

	  return false;
  }
   function sql_query_ultimaregra( $matric ) {
	  $sql = "
		     select x43_regra 
		       from aguacortematmov
		            inner join aguacortemat on x41_codcortemat = x42_codcortemat
		            inner join aguacortesituacao on x43_codsituacao = x42_codsituacao
		      where x41_matric = $matric
		   order by x42_data   desc, 
                x42_codmov desc
		      limit 1 ";
    return $sql;
  }
}
?>