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

//MODULO: Farmacia
//CLASSE DA ENTIDADE far_retiradaitens
class cl_far_retiradaitens { 
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
   var $fa06_i_codigo = 0; 
   var $fa06_t_posologia = null; 
   var $fa06_i_retirada = 0; 
   var $fa06_i_matersaude = 0; 
   var $fa06_f_quant = 0; 
   var $fa06_t_controlado = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa06_i_codigo = int4 = Código 
                 fa06_t_posologia = text = Posologia 
                 fa06_i_retirada = int4 = Retirada 
                 fa06_i_matersaude = int4 = Medicamento 
                 fa06_f_quant = float8 = Quantidade 
                 fa06_t_controlado = char(2) = Med.Controlado 
                 ";
   //funcao construtor da classe 
   function cl_far_retiradaitens() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_retiradaitens"); 
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
       $this->fa06_i_codigo = ($this->fa06_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa06_i_codigo"]:$this->fa06_i_codigo);
       $this->fa06_t_posologia = ($this->fa06_t_posologia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa06_t_posologia"]:$this->fa06_t_posologia);
       $this->fa06_i_retirada = ($this->fa06_i_retirada == ""?@$GLOBALS["HTTP_POST_VARS"]["fa06_i_retirada"]:$this->fa06_i_retirada);
       $this->fa06_i_matersaude = ($this->fa06_i_matersaude == ""?@$GLOBALS["HTTP_POST_VARS"]["fa06_i_matersaude"]:$this->fa06_i_matersaude);
       $this->fa06_f_quant = ($this->fa06_f_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["fa06_f_quant"]:$this->fa06_f_quant);
       $this->fa06_t_controlado = ($this->fa06_t_controlado == ""?@$GLOBALS["HTTP_POST_VARS"]["fa06_t_controlado"]:$this->fa06_t_controlado);
     }else{
       $this->fa06_i_codigo = ($this->fa06_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa06_i_codigo"]:$this->fa06_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa06_i_codigo){ 
      $this->atualizacampos();
      if($this->fa06_t_posologia == null ){
        $this->fa06_t_posologia='';
      }
     if($this->fa06_i_retirada == null ){ 
       $this->erro_sql = " Campo Retirada nao Informado.";
       $this->erro_campo = "fa06_i_retirada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa06_i_matersaude == null ){ 
       $this->erro_sql = " Campo Medicamento nao Informado.";
       $this->erro_campo = "fa06_i_matersaude";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa06_f_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "fa06_f_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa06_t_controlado == null ){
       $this->fa06_t_controlado='';
     }
     if($fa06_i_codigo == "" || $fa06_i_codigo == null ){
       $result = db_query("select nextval('faretiradait_fa06_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: faretiradait_fa06_i_codigo_seq do campo: fa06_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa06_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from faretiradait_fa06_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa06_i_codigo)){
         $this->erro_sql = " Campo fa06_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa06_i_codigo = $fa06_i_codigo; 
       }
     }
     if(($this->fa06_i_codigo == null) || ($this->fa06_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa06_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_retiradaitens(
                                       fa06_i_codigo 
                                      ,fa06_t_posologia 
                                      ,fa06_i_retirada 
                                      ,fa06_i_matersaude 
                                      ,fa06_f_quant 
                                      ,fa06_t_controlado 
                       )
                values (
                                $this->fa06_i_codigo 
                               ,'$this->fa06_t_posologia' 
                               ,$this->fa06_i_retirada 
                               ,$this->fa06_i_matersaude 
                               ,$this->fa06_f_quant 
                               ,'$this->fa06_t_controlado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_retiradaitens ($this->fa06_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_retiradaitens já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_retiradaitens ($this->fa06_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa06_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa06_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12133,'$this->fa06_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2109,12133,'','".AddSlashes(pg_result($resaco,0,'fa06_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2109,12134,'','".AddSlashes(pg_result($resaco,0,'fa06_t_posologia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2109,12135,'','".AddSlashes(pg_result($resaco,0,'fa06_i_retirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2109,12190,'','".AddSlashes(pg_result($resaco,0,'fa06_i_matersaude'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2109,12204,'','".AddSlashes(pg_result($resaco,0,'fa06_f_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2109,12803,'','".AddSlashes(pg_result($resaco,0,'fa06_t_controlado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa06_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update far_retiradaitens set ";
     $virgula = "";
     if(trim($this->fa06_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa06_i_codigo"])){ 
       $sql  .= $virgula." fa06_i_codigo = $this->fa06_i_codigo ";
       $virgula = ",";
       if(trim($this->fa06_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "fa06_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa06_t_posologia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa06_t_posologia"])){ 
       $sql  .= $virgula." fa06_t_posologia = '$this->fa06_t_posologia' ";
       $virgula = ",";
       if(trim($this->fa06_t_posologia) == null ){ 
         $this->erro_sql = " Campo Posologia nao Informado.";
         $this->erro_campo = "fa06_t_posologia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa06_i_retirada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa06_i_retirada"])){ 
       $sql  .= $virgula." fa06_i_retirada = $this->fa06_i_retirada ";
       $virgula = ",";
       if(trim($this->fa06_i_retirada) == null ){ 
         $this->erro_sql = " Campo Retirada nao Informado.";
         $this->erro_campo = "fa06_i_retirada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa06_i_matersaude)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa06_i_matersaude"])){ 
       $sql  .= $virgula." fa06_i_matersaude = $this->fa06_i_matersaude ";
       $virgula = ",";
       if(trim($this->fa06_i_matersaude) == null ){ 
         $this->erro_sql = " Campo Medicamento nao Informado.";
         $this->erro_campo = "fa06_i_matersaude";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa06_f_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa06_f_quant"])){ 
       $sql  .= $virgula." fa06_f_quant = $this->fa06_f_quant ";
       $virgula = ",";
       if(trim($this->fa06_f_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "fa06_f_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa06_t_controlado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa06_t_controlado"])){ 
       $sql  .= $virgula." fa06_t_controlado = '$this->fa06_t_controlado' ";
       $virgula = ",";
       if(trim($this->fa06_t_controlado) == null ){ 
         $this->erro_sql = " Campo Med.Controlado nao Informado.";
         $this->erro_campo = "fa06_t_controlado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa06_i_codigo!=null){
       $sql .= " fa06_i_codigo = $this->fa06_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa06_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12133,'$this->fa06_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa06_i_codigo"]) || $this->fa06_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2109,12133,'".AddSlashes(pg_result($resaco,$conresaco,'fa06_i_codigo'))."','$this->fa06_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa06_t_posologia"]) || $this->fa06_t_posologia != "")
           $resac = db_query("insert into db_acount values($acount,2109,12134,'".AddSlashes(pg_result($resaco,$conresaco,'fa06_t_posologia'))."','$this->fa06_t_posologia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa06_i_retirada"]) || $this->fa06_i_retirada != "")
           $resac = db_query("insert into db_acount values($acount,2109,12135,'".AddSlashes(pg_result($resaco,$conresaco,'fa06_i_retirada'))."','$this->fa06_i_retirada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa06_i_matersaude"]) || $this->fa06_i_matersaude != "")
           $resac = db_query("insert into db_acount values($acount,2109,12190,'".AddSlashes(pg_result($resaco,$conresaco,'fa06_i_matersaude'))."','$this->fa06_i_matersaude',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa06_f_quant"]) || $this->fa06_f_quant != "")
           $resac = db_query("insert into db_acount values($acount,2109,12204,'".AddSlashes(pg_result($resaco,$conresaco,'fa06_f_quant'))."','$this->fa06_f_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa06_t_controlado"]) || $this->fa06_t_controlado != "")
           $resac = db_query("insert into db_acount values($acount,2109,12803,'".AddSlashes(pg_result($resaco,$conresaco,'fa06_t_controlado'))."','$this->fa06_t_controlado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_retiradaitens nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa06_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_retiradaitens nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa06_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa06_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa06_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa06_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12133,'$fa06_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2109,12133,'','".AddSlashes(pg_result($resaco,$iresaco,'fa06_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2109,12134,'','".AddSlashes(pg_result($resaco,$iresaco,'fa06_t_posologia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2109,12135,'','".AddSlashes(pg_result($resaco,$iresaco,'fa06_i_retirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2109,12190,'','".AddSlashes(pg_result($resaco,$iresaco,'fa06_i_matersaude'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2109,12204,'','".AddSlashes(pg_result($resaco,$iresaco,'fa06_f_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2109,12803,'','".AddSlashes(pg_result($resaco,$iresaco,'fa06_t_controlado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from far_retiradaitens
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa06_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa06_i_codigo = $fa06_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_retiradaitens nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa06_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_retiradaitens nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa06_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa06_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_retiradaitens";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $fa06_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_retiradaitens ";
     $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = far_retiradaitens.fa06_i_matersaude";
     $sql .= "      inner join far_retirada  on  far_retirada.fa04_i_codigo = far_retiradaitens.fa06_i_retirada";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";     
     $sql2 = "";
     if($dbwhere==""){
       if($fa06_i_codigo!=null ){
         $sql2 .= " where far_retiradaitens.fa06_i_codigo = $fa06_i_codigo "; 
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
   function sql_query_file ( $fa06_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_retiradaitens ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa06_i_codigo!=null ){
         $sql2 .= " where far_retiradaitens.fa06_i_codigo = $fa06_i_codigo "; 
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
   function sql_query_matrequitens($m40_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matrequi ";
    $sql .= "      inner join db_depart      on db_depart.coddepto                 = matrequi.m40_depto";
    $sql .= "      inner join matrequiitem   on  matrequiitem.m41_codmatrequi = matrequi.m40_codigo";
    $sql .= "      inner join matmater   on  matmater.m60_codmater = matrequiitem.m41_codmatmater";
    $sql .= "     inner join matestoque on matmater.m60_codmater = matestoque.m70_codmatmater and matestoque.m70_coddepto= db_depart.coddepto";
    $sql .= "    inner join matestoqueitem on m71_codmatestoque = m70_codigo";
    $sql .= "    left join matestoqueitemlote on m77_matestoqueitem = m71_codlanc";
    $sql .= "      inner join matunid a  on  a.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      inner join matmaterunisai on matmaterunisai.m62_codmater = matmater.m60_codmater";
    $sql .= "      inner join matunid b  on  b.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      left  join atendrequiitem on atendrequiitem.m43_codmatrequiitem = matrequiitem.m41_codigo";
    $sql2 = "";
    if ($dbwhere=="") {
      if ($m40_codigo!=null ) {
        $sql2 .= " where matrequi.m40_codigo = $m40_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_posologia ( $fa06_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
    $sql .= " from far_retiradaitens ";
     $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = far_retiradaitens.fa06_i_matersaude";
     $sql .= "      inner join far_retirada  on  far_retirada.fa04_i_codigo = far_retiradaitens.fa06_i_retirada";
     $sql .= "      inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = far_retirada.fa04_i_tiporeceita";
     $sql .= "      left join far_retiradarequisitante  on  far_retiradarequisitante.fa08_i_retirada = far_retirada.fa04_i_codigo";
     $sql .= "      left join cgs_und  on  cgs_und.z01_i_cgsund = far_retiradarequisitante.fa08_i_cgsund";
     $sql .= "      inner join far_retiradarequi  on  far_retiradarequi.fa07_i_retirada = far_retirada.fa04_i_codigo";
     $sql .= "      inner join matrequi  on  matrequi.m40_codigo = far_retiradarequi.fa07_i_matrequi";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";
     $sql .= "      inner join unidades on unidades.sd02_i_codigo= far_retirada.fa04_i_unidades";
     $sql .= "      inner join db_depart on db_depart.coddepto = unidades.sd02_i_codigo ";
     $sql .= "      left join far_retiradaitemlote on fa09_i_retiradaitens= far_retiradaitens.fa06_i_codigo";
     $sql .= "      left join matestoqueitem on m71_codlanc = far_retiradaitemlote.fa09_i_matestoqueitem";
     $sql .= "      left join matestoqueitemlote on m77_matestoqueitem = matestoqueitem.m71_codlanc";
     $sql .= "      inner join matunid on matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql2 = "";
     if($dbwhere==""){
       if($fa06_i_codigo!=null ){
         $sql2 .= " where matrequi.m40_codigo = $m40_codigo"; 
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
   function sql_query_retiradaitens( $fa06_i_codigo=null,$campos="*",$ordem=null,$dbwhere="",$group_by=null){ 
     $sql = "select ";
     if($campos != "*"){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from far_retiradaitens ";
     $sql .= " inner join far_matersaude on far_matersaude.fa01_i_codigo = far_retiradaitens.fa06_i_matersaude";
     $sql .= " inner join matmater on matmater.m60_codmater = far_matersaude.fa01_i_codmater";
     $sql .= " inner join far_retirada on far_retirada.fa04_i_codigo =far_retiradaitens.fa06_i_retirada";
     $sql .= " inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = far_retirada.fa04_i_tiporeceita";//foi colocado 30/10/2008 rela
     $sql .= " inner join unidades on unidades.sd02_i_codigo= far_retirada.fa04_i_unidades";
     $sql .= " left join medicos  on  medicos.sd03_i_codigo = far_retirada.fa04_i_profissional";//foi colocado 30/10/2008 relatorio
     $sql .= " left join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm"; // 30/10/2008 usando relatorio
     $sql .= " inner join db_depart on db_depart.coddepto = unidades.sd02_i_codigo ";
     $sql .= " left join far_retiradaitemlote on fa09_i_retiradaitens= far_retiradaitens.fa06_i_codigo";
     $sql .= " left join matestoqueitem on m71_codlanc = far_retiradaitemlote.fa09_i_matestoqueitem";
     $sql .= " left join matestoqueitemlote on m77_matestoqueitem = matestoqueitem.m71_codlanc";
     $sql .= " inner join matunid on matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql2 = "";
     if($dbwhere==""){
       if($fa06_i_codigo!=null ){
         $sql2 .= " where far_retiradaitens.fa06_i_retirada= $fa06_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 .= " where $dbwhere";
     }

     if( $group_by != null ){
        $sql2 .= " group by $group_by ";
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

 /*  traz as retiradas feitas de medicamentos continuado, mas sem fazer as comparacoes das datas do periodo do
  *  medicamento continuado, o que pode trazer retiradas que nao sao de medicamentos continuados.
  *  Para garantir que somente retiradas de medicamentos continuados e de tratamentos em andamento venham, 
  *  passe o seguinte codigo como parametro:
  *
  *     (far_controlemed.fa10_d_datafim is null or far_controlemed.fa10_d_datafim >= $dData_atual)
  *     and ((far_controlemed.fa10_d_datafim is null and far_retirada.fa04_d_data > far_controlemed.fa10_d_dataini) or
  *         (far_controlemed.fa10_d_datafim is not null 
  *          and far_retirada.fa04_d_data between far_controlemed.fa10_d_dataini and far_controlemed.fa10_d_datafim)) ";
  */
  function sql_query_retiradas_continuados($m40_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from far_retiradaitens ";
    $sql .= "   inner join far_retirada on far_retirada.fa04_i_codigo = far_retiradaitens.fa06_i_retirada ";
    $sql .= "   inner join far_controle on far_controle.fa11_i_cgsund = far_retirada.fa04_i_cgsund ";
    $sql .= "   inner join far_controlemed on far_controlemed.fa10_i_controle = far_controle.fa11_i_codigo ";
    $sql .= "     and far_controlemed.fa10_i_medicamento = far_retiradaitens.fa06_i_matersaude ";
    $sql .= "   inner join far_matersaude on far_matersaude.fa01_i_codigo = far_controlemed.fa10_i_medicamento ";
    $sql .= "   inner join matmater on matmater.m60_codmater = far_matersaude.fa01_i_codmater ";
    $sql .= "   inner join matunid on matunid.m61_codmatunid = matmater.m60_codmatunid ";
    $sql .= "   inner join cgs_und on cgs_und.z01_i_cgsund = far_controle.fa11_i_cgsund ";
    $sql .= "   inner join db_depart on db_depart.coddepto = far_retirada.fa04_i_unidades ";
    $sql2 = "";

    if ($dbwhere=="") {
      if ($mfa06_i_codigo!=null ) {
        $sql2 = " where far_retiradaitens.fa06_i_codigo = $fa06_i_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  /**
   * Seleciona todas as teridas de um determinado CGS
   * 
   * @param unknown_type $cgs
   * @param unknown_type $campos
   * @param unknown_type $ordem
   * @param unknown_type $dbwhere
   */
  function sql_query_historicoretiradas($z01_i_cgsund=null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from far_retiradaitens ";
    $sql .= "inner join far_retirada          on fa06_i_retirada=fa04_i_codigo ";
    $sql .= "inner join db_depart on db_depart.coddepto = far_retirada.fa04_i_unidades ";
    $sql .= "inner join cgs_und               on z01_i_cgsund = fa04_i_cgsund ";
    $sql .= "inner join far_matersaude        on fa06_i_matersaude=fa01_i_codigo ";
    $sql .= "inner join matmater              on matmater.m60_codmater = far_matersaude.fa01_i_codmater ";
    $sql .= "inner join matunid               on matunid.m61_codmatunid = matmater.m60_codmatunid ";
    $sql .= " left join far_listacontroladomed on far_listacontroladomed.fa35_i_codigo = far_matersaude.fa01_i_listacontroladomed ";
    $sql .= " left join far_listacontrolado    on far_listacontrolado.fa15_i_codigo = far_listacontroladomed.fa35_i_listacontrolado ";
    $sql .= " left join far_retiradarequi      on fa04_i_codigo=fa07_i_retirada ";
    $sql .= " left join far_retiradaitemlote   on fa06_i_codigo=fa09_i_retiradaitens ";
    $sql .= " left join matestoqueitemlote     on fa09_i_matestoqueitem=m77_matestoqueitem ";
    $sql .= " left join db_usuarios            on id_usuario = fa04_i_dbusuario ";
    
    $sql2 = "";

    if ($dbwhere=="") {
      if ($z01_i_cgsund!=null ) {
        $sql2 = " where cgs_und.z01_i_cgsund = $z01_i_cgsund ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_historicoretiradasdevolucoes($z01_i_cgsund=null,$campos="*",$ordem=null,$dbwhere1="", $dbwhere2="", 
                                                  $sGroupBy = '', $sWhereDepartamentos = '') {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql2 = "";
    $sql3 = "";

    if ($dbwhere1=="") {
      if ($z01_i_cgsund!=null ) {
        $sql2 = " where z01_i_cgsund = $z01_i_cgsund ";
      }
    } else if ($dbwhere1 != "") {
      $sql2 = " where $dbwhere1";
    }

    if ($dbwhere2=="") {
      if ($z01_i_cgsund!=null ) {
        $sql3 = " where z01_i_cgsund = $z01_i_cgsund ";
      }
    } else if ($dbwhere2 != "") {
      $sql3 = " where $dbwhere2";
    }

    $sql .= " from ((select cgs_und.*, db_usuarios.*, matestoqueitemlote.*, far_retiradarequi.*, matmater.*, ";
    $sql .= ' matunid.*, far_matersaude.*, fa06_t_posologia, fa06_f_quant, null as fa23_c_motivo, ';
    $sql .= ' null as fa23_i_cancelamento, null as fa23_i_quantidade, fa04_d_data, fa04_i_unidades, null as fa22_d_data, ';
    $sql .= ' 1 as tipo, fa06_i_codigo, fa04_tiporetirada from far_retiradaitens ';
    $sql .= " inner join far_retirada          on fa06_i_retirada=fa04_i_codigo ";
    $sql .= " inner join cgs_und               on z01_i_cgsund = fa04_i_cgsund ";
    $sql .= " inner join far_matersaude        on fa06_i_matersaude=fa01_i_codigo ";
    $sql .= " inner join matmater              on matmater.m60_codmater = far_matersaude.fa01_i_codmater ";
    $sql .= " inner join matunid               on matunid.m61_codmatunid = matmater.m60_codmatunid ";
    $sql .= " left join far_retiradarequi      on fa04_i_codigo=fa07_i_retirada ";
    $sql .= " left join far_retiradaitemlote   on fa06_i_codigo=fa09_i_retiradaitens ";
    $sql .= " left join matestoqueitemlote     on fa09_i_matestoqueitem=m77_matestoqueitem ";
    $sql .= " left join matestoqueitem         on m71_codlanc = m77_matestoqueitem";
    $sql .= " left join matestoque             on m70_codigo  = m71_codmatestoque";
    if (!empty($sWhereDepartamentos)) {
      $sql .= " $sWhereDepartamentos ";
    }
    $sql .= " left join db_usuarios            on id_usuario = fa04_i_dbusuario $sql2) ";

    $sql .= '                                union ';

    $sql .= ' (select cgs_und.*, db_usuarios.*, matestoqueitemlote.*, far_retiradarequi.*, matmater.*, ';
    $sql .= ' matunid.*, far_matersaude.*, null as fa06_t_posologia, null as fa06_f_quant, fa23_c_motivo, ';
    $sql .= ' fa23_i_cancelamento, fa23_i_quantidade, null as fa04_d_data, fa04_i_unidades, fa22_d_data, ';
    $sql .= " 2 as tipo, fa06_i_codigo, fa04_tiporetirada from far_retiradaitens ";
    $sql .= " inner join far_retirada          on fa06_i_retirada=fa04_i_codigo ";
    $sql .= ' inner join far_devolucaomed on far_devolucaomed.fa23_i_retiradaitens = far_retiradaitens.fa06_i_codigo ';
    $sql .= ' inner join far_devolucao on far_devolucao.fa22_i_codigo = far_devolucaomed.fa23_i_devolucao ';
    $sql .= " inner join cgs_und               on z01_i_cgsund = fa22_i_cgsund ";
    $sql .= " inner join far_matersaude        on fa06_i_matersaude=fa01_i_codigo ";
    $sql .= " inner join matmater              on matmater.m60_codmater = far_matersaude.fa01_i_codmater ";
    $sql .= " inner join matunid               on matunid.m61_codmatunid = matmater.m60_codmatunid ";
    $sql .= " left join far_retiradarequi      on fa04_i_codigo=fa07_i_retirada ";
    $sql .= " left join far_retiradaitemlote   on fa06_i_codigo=fa09_i_retiradaitens ";
    $sql .= " left join matestoqueitemlote     on fa09_i_matestoqueitem=m77_matestoqueitem ";
    $sql .= " left join matestoqueitem         on m71_codlanc = m77_matestoqueitem";
    $sql .= " left join matestoque             on m70_codigo  = m71_codmatestoque";
    if (!empty($sWhereDepartamentos)) {
      $sql .= " $sWhereDepartamentos ";
    }
    $sql .= " left join db_usuarios            on id_usuario = fa22_i_login $sql3)) as a ";

    if (!empty($sGroupBy)) {
      $sql .= ' group by '.$sGroupBy;
    }

    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  
  
  
  function sql_query_mapaconsolidado($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from far_retiradaitens ";
    $sSql .= "   inner join far_retirada "; 
    $sSql .= "     on far_retirada.fa04_i_codigo = far_retiradaitens.fa06_i_retirada ";
    $sSql .= "   inner join far_matersaude ";  
    $sSql .= "     on far_matersaude.fa01_i_codigo = far_retiradaitens.fa06_i_matersaude ";
    $sSql .= "   left join far_tiporeceita ";
    $sSql .= "     on far_tiporeceita.fa03_i_codigo = far_retirada.fa04_i_tiporeceita ";
    $sSql .= "   left join far_controlemed ";
    $sSql .= "     on far_retiradaitens.fa06_i_matersaude = far_controlemed.fa10_i_medicamento ";
    $sSql .= "   left join far_controle ";
    $sSql .= "     on  far_controle.fa11_i_codigo = far_controlemed.fa10_i_controle "; 
    $sSql .= "   inner join matmater ";
    $sSql .= "     on  matmater.m60_codmater = far_matersaude.fa01_i_codmater ";
    $sSql .= "   left join far_medanvisa ";
    $sSql .= "     on fa14_i_codigo = far_matersaude.fa01_i_medanvisa ";
    $sSql .= "   left join far_listacontroladomed ";
    $sSql .= "     on far_listacontroladomed.fa35_i_medanvisa = far_medanvisa.fa14_i_codigo ";
    $sSql .= "   left join far_listacontrolado ";
    $sSql .= "     on far_listacontrolado.fa15_i_codigo = far_listacontroladomed.fa35_i_listacontrolado ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where far_retirada.fa04_i_codigo = $iCodigo "; 
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  } 
  
}
?>