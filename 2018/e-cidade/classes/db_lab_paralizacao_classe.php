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

//MODULO: laboratorio
//CLASSE DA ENTIDADE lab_paralizacao
class cl_lab_paralizacao { 
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
   var $la37_i_codigo = 0; 
   var $la37_i_laboratorio = 0; 
   var $la37_d_ini_dia = null; 
   var $la37_d_ini_mes = null; 
   var $la37_d_ini_ano = null; 
   var $la37_d_ini = null; 
   var $la37_d_fim_dia = null; 
   var $la37_d_fim_mes = null; 
   var $la37_d_fim_ano = null; 
   var $la37_d_fim = null; 
   var $la37_c_horaini = null; 
   var $la37_c_horafim = null; 
   var $la37_i_motivo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la37_i_codigo = int4 = Código 
                 la37_i_laboratorio = int4 = Laboratório 
                 la37_d_ini = date = Início 
                 la37_d_fim = date = Fim 
                 la37_c_horaini = char(5) = Hora Inicial 
                 la37_c_horafim = char(5) = Hora final 
                 la37_i_motivo = int4 = Motivo da paralisação 
                 ";
   //funcao construtor da classe 
   function cl_lab_paralizacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_paralizacao"); 
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
       $this->la37_i_codigo = ($this->la37_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la37_i_codigo"]:$this->la37_i_codigo);
       $this->la37_i_laboratorio = ($this->la37_i_laboratorio == ""?@$GLOBALS["HTTP_POST_VARS"]["la37_i_laboratorio"]:$this->la37_i_laboratorio);
       if($this->la37_d_ini == ""){
         $this->la37_d_ini_dia = ($this->la37_d_ini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la37_d_ini_dia"]:$this->la37_d_ini_dia);
         $this->la37_d_ini_mes = ($this->la37_d_ini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la37_d_ini_mes"]:$this->la37_d_ini_mes);
         $this->la37_d_ini_ano = ($this->la37_d_ini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la37_d_ini_ano"]:$this->la37_d_ini_ano);
         if($this->la37_d_ini_dia != ""){
            $this->la37_d_ini = $this->la37_d_ini_ano."-".$this->la37_d_ini_mes."-".$this->la37_d_ini_dia;
         }
       }
       if($this->la37_d_fim == ""){
         $this->la37_d_fim_dia = ($this->la37_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la37_d_fim_dia"]:$this->la37_d_fim_dia);
         $this->la37_d_fim_mes = ($this->la37_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la37_d_fim_mes"]:$this->la37_d_fim_mes);
         $this->la37_d_fim_ano = ($this->la37_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la37_d_fim_ano"]:$this->la37_d_fim_ano);
         if($this->la37_d_fim_dia != ""){
            $this->la37_d_fim = $this->la37_d_fim_ano."-".$this->la37_d_fim_mes."-".$this->la37_d_fim_dia;
         }
       }
       $this->la37_c_horaini = ($this->la37_c_horaini == ""?@$GLOBALS["HTTP_POST_VARS"]["la37_c_horaini"]:$this->la37_c_horaini);
       $this->la37_c_horafim = ($this->la37_c_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["la37_c_horafim"]:$this->la37_c_horafim);
       $this->la37_i_motivo = ($this->la37_i_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["la37_i_motivo"]:$this->la37_i_motivo);
     }else{
       $this->la37_i_codigo = ($this->la37_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la37_i_codigo"]:$this->la37_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la37_i_codigo){ 
      $this->atualizacampos();
     if($this->la37_i_laboratorio == null ){ 
       $this->erro_sql = " Campo Laboratório nao Informado.";
       $this->erro_campo = "la37_i_laboratorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la37_d_ini == null ){ 
       $this->erro_sql = " Campo Início nao Informado.";
       $this->erro_campo = "la37_d_ini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la37_d_fim == null ){ 
       $this->erro_sql = " Campo Fim nao Informado.";
       $this->erro_campo = "la37_d_fim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la37_i_motivo == null ){ 
       $this->erro_sql = " Campo Motivo da paralisação nao Informado.";
       $this->erro_campo = "la37_i_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la37_i_codigo == "" || $la37_i_codigo == null ){
       $result = db_query("select nextval('lab_paralizacao_la37_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_paralizacao_la37_i_codigo_seq do campo: la37_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la37_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_paralizacao_la37_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la37_i_codigo)){
         $this->erro_sql = " Campo la37_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la37_i_codigo = $la37_i_codigo; 
       }
     }
     if(($this->la37_i_codigo == null) || ($this->la37_i_codigo == "") ){ 
       $this->erro_sql = " Campo la37_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_paralizacao(
                                       la37_i_codigo 
                                      ,la37_i_laboratorio 
                                      ,la37_d_ini 
                                      ,la37_d_fim 
                                      ,la37_c_horaini 
                                      ,la37_c_horafim 
                                      ,la37_i_motivo 
                       )
                values (
                                $this->la37_i_codigo 
                               ,$this->la37_i_laboratorio 
                               ,".($this->la37_d_ini == "null" || $this->la37_d_ini == ""?"null":"'".$this->la37_d_ini."'")." 
                               ,".($this->la37_d_fim == "null" || $this->la37_d_fim == ""?"null":"'".$this->la37_d_fim."'")." 
                               ,'$this->la37_c_horaini' 
                               ,'$this->la37_c_horafim' 
                               ,$this->la37_i_motivo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lab_paralizacao ($this->la37_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lab_paralizacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lab_paralizacao ($this->la37_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la37_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la37_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15799,'$this->la37_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2778,15799,'','".AddSlashes(pg_result($resaco,0,'la37_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2778,15800,'','".AddSlashes(pg_result($resaco,0,'la37_i_laboratorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2778,15801,'','".AddSlashes(pg_result($resaco,0,'la37_d_ini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2778,15802,'','".AddSlashes(pg_result($resaco,0,'la37_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2778,15803,'','".AddSlashes(pg_result($resaco,0,'la37_c_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2778,15804,'','".AddSlashes(pg_result($resaco,0,'la37_c_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2778,17720,'','".AddSlashes(pg_result($resaco,0,'la37_i_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la37_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_paralizacao set ";
     $virgula = "";
     if(trim($this->la37_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la37_i_codigo"])){ 
       $sql  .= $virgula." la37_i_codigo = $this->la37_i_codigo ";
       $virgula = ",";
       if(trim($this->la37_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "la37_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la37_i_laboratorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la37_i_laboratorio"])){ 
       $sql  .= $virgula." la37_i_laboratorio = $this->la37_i_laboratorio ";
       $virgula = ",";
       if(trim($this->la37_i_laboratorio) == null ){ 
         $this->erro_sql = " Campo Laboratório nao Informado.";
         $this->erro_campo = "la37_i_laboratorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la37_d_ini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la37_d_ini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la37_d_ini_dia"] !="") ){ 
       $sql  .= $virgula." la37_d_ini = '$this->la37_d_ini' ";
       $virgula = ",";
       if(trim($this->la37_d_ini) == null ){ 
         $this->erro_sql = " Campo Início nao Informado.";
         $this->erro_campo = "la37_d_ini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la37_d_ini_dia"])){ 
         $sql  .= $virgula." la37_d_ini = null ";
         $virgula = ",";
         if(trim($this->la37_d_ini) == null ){ 
           $this->erro_sql = " Campo Início nao Informado.";
           $this->erro_campo = "la37_d_ini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la37_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la37_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la37_d_fim_dia"] !="") ){ 
       $sql  .= $virgula." la37_d_fim = '$this->la37_d_fim' ";
       $virgula = ",";
       if(trim($this->la37_d_fim) == null ){ 
         $this->erro_sql = " Campo Fim nao Informado.";
         $this->erro_campo = "la37_d_fim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la37_d_fim_dia"])){ 
         $sql  .= $virgula." la37_d_fim = null ";
         $virgula = ",";
         if(trim($this->la37_d_fim) == null ){ 
           $this->erro_sql = " Campo Fim nao Informado.";
           $this->erro_campo = "la37_d_fim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la37_c_horaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la37_c_horaini"])){ 
       $sql  .= $virgula." la37_c_horaini = '$this->la37_c_horaini' ";
       $virgula = ",";
     }
     if(trim($this->la37_c_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la37_c_horafim"])){ 
       $sql  .= $virgula." la37_c_horafim = '$this->la37_c_horafim' ";
       $virgula = ",";
     }
     if(trim($this->la37_i_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la37_i_motivo"])){ 
       $sql  .= $virgula." la37_i_motivo = $this->la37_i_motivo ";
       $virgula = ",";
       if(trim($this->la37_i_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo da paralisação nao Informado.";
         $this->erro_campo = "la37_i_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la37_i_codigo!=null){
       $sql .= " la37_i_codigo = $this->la37_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la37_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15799,'$this->la37_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la37_i_codigo"]) || $this->la37_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2778,15799,'".AddSlashes(pg_result($resaco,$conresaco,'la37_i_codigo'))."','$this->la37_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la37_i_laboratorio"]) || $this->la37_i_laboratorio != "")
           $resac = db_query("insert into db_acount values($acount,2778,15800,'".AddSlashes(pg_result($resaco,$conresaco,'la37_i_laboratorio'))."','$this->la37_i_laboratorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la37_d_ini"]) || $this->la37_d_ini != "")
           $resac = db_query("insert into db_acount values($acount,2778,15801,'".AddSlashes(pg_result($resaco,$conresaco,'la37_d_ini'))."','$this->la37_d_ini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la37_d_fim"]) || $this->la37_d_fim != "")
           $resac = db_query("insert into db_acount values($acount,2778,15802,'".AddSlashes(pg_result($resaco,$conresaco,'la37_d_fim'))."','$this->la37_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la37_c_horaini"]) || $this->la37_c_horaini != "")
           $resac = db_query("insert into db_acount values($acount,2778,15803,'".AddSlashes(pg_result($resaco,$conresaco,'la37_c_horaini'))."','$this->la37_c_horaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la37_c_horafim"]) || $this->la37_c_horafim != "")
           $resac = db_query("insert into db_acount values($acount,2778,15804,'".AddSlashes(pg_result($resaco,$conresaco,'la37_c_horafim'))."','$this->la37_c_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la37_i_motivo"]) || $this->la37_i_motivo != "")
           $resac = db_query("insert into db_acount values($acount,2778,17720,'".AddSlashes(pg_result($resaco,$conresaco,'la37_i_motivo'))."','$this->la37_i_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_paralizacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la37_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_paralizacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la37_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la37_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la37_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la37_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15799,'$la37_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2778,15799,'','".AddSlashes(pg_result($resaco,$iresaco,'la37_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2778,15800,'','".AddSlashes(pg_result($resaco,$iresaco,'la37_i_laboratorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2778,15801,'','".AddSlashes(pg_result($resaco,$iresaco,'la37_d_ini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2778,15802,'','".AddSlashes(pg_result($resaco,$iresaco,'la37_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2778,15803,'','".AddSlashes(pg_result($resaco,$iresaco,'la37_c_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2778,15804,'','".AddSlashes(pg_result($resaco,$iresaco,'la37_c_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2778,17720,'','".AddSlashes(pg_result($resaco,$iresaco,'la37_i_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_paralizacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la37_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la37_i_codigo = $la37_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_paralizacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la37_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_paralizacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la37_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la37_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_paralizacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la37_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_paralizacao ";
     $sql .= "      inner join sau_motivo_ausencia  on  sau_motivo_ausencia.s139_i_codigo = lab_paralizacao.la37_i_motivo";
     $sql .= "      inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_paralizacao.la37_i_laboratorio";
     $sql .= "      left  join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = lab_laboratorio.la02_i_turnoatend";
     $sql2 = "";
     if($dbwhere==""){
       if($la37_i_codigo!=null ){
         $sql2 .= " where lab_paralizacao.la37_i_codigo = $la37_i_codigo "; 
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
   function sql_query_file ( $la37_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_paralizacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($la37_i_codigo!=null ){
         $sql2 .= " where lab_paralizacao.la37_i_codigo = $la37_i_codigo "; 
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
   function sql_query_lab_setorexame ( $la37_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from lab_paralizacao ";
     $sql .= "      inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_paralizacao.la37_i_laboratorio ";
     $sql .= "      inner join lab_labsetor    on  lab_labsetor.la24_i_laboratorio = lab_laboratorio.la02_i_codigo ";
     $sql .= "      inner join lab_setorexame   on  lab_setorexame.la09_i_labsetor = lab_labsetor.la24_i_codigo ";
     $sql .= "      left  join sau_turnoatend   on  sau_turnoatend.sd43_cod_turnat = lab_laboratorio.la02_i_turnoatend ";
     $sql2 = "";
     if($dbwhere==""){
       if($la37_i_codigo!=null ){
         $sql2 .= " where lab_paralizacao.la37_i_codigo = $la37_i_codigo ";
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
   function HoraToMin($hora){
    $hora=str_replace("'","",$hora);
    $aVet=explode(":",$hora);
    $minutos=( ((int)$aVet[0]) *60)+ ((int)$aVet[1]);
    return $minutos;
 }
 function PeriodoPertense($ini_1,$fim_1,$ini_2,$fim_2){
    if( ( ($ini_1>=$ini_2)&&($ini_1<=$fim_2) )
             ||
        ( ($fim_1>=$ini_2)&&($fim_1<=$fim_2) )
      ){
       return true;
    }else{
       return false;
    }
 }

   function sql_query_paralisacao ( $la37_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_paralizacao ";
     $sql .= "      inner join sau_motivo_ausencia  on  sau_motivo_ausencia.s139_i_codigo = lab_paralizacao.la37_i_motivo";
     $sql .= "      inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_paralizacao.la37_i_laboratorio";
     $sql .= "      inner join lab_labsetor  on  lab_labsetor.la24_i_laboratorio = lab_laboratorio.la02_i_codigo";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_labsetor = lab_labsetor.la24_i_codigo";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
     $sql2 = "";
     if($dbwhere==""){
       if($la37_i_codigo!=null ){
         $sql2 .= " where lab_paralizacao.la37_i_codigo = $la37_i_codigo "; 
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

   function laboratorioparalisado($la09_i_codigo, $sHoraIni, $sHoraFim, $dData) {
 
    $sMotivo = ''; 
    $sSep    = ''; 
    $sSql    = $this->sql_query_paralisacao('', 'la37_c_horaini, la37_c_horafim, s139_c_descr', '', 
                                            " la09_i_codigo = $la09_i_codigo and ($dData >= la37_d_ini and $dData <= la37_d_fim)"
                                           );  
    $rs      = $this->sql_record($sSql);
    for ($iCont = 0; $iCont < $this->numrows; $iCont++) {
            
      $oParalisacoes = db_utils::fieldsMemory($rs, $iCont);
      if ($oParalisacoes->la37_c_horaini != ''){
 
        $iMinHoraIni = $this->HoraToMin($sHoraIni);
        $iMinHoraFim = $this->HoraToMin($sHoraFim);
        $iMinAusIni  = $this->HoraToMin($oParalisacoes->la37_c_horaini);
        $iMinAusFim  = $this->HoraToMin($oParalisacoes->la37_c_horafim);
        if ($this->PeriodoPertense($iMinHoraIni, $iMinHoraFim, $iMinAusIni, $iMinAusFim)) {
 
          $sMotivo .= $sSep.$oParalisacoes->la37_c_horaini.' - '.$oParalisacoes->la37_c_horafim.' - ';
          $sMotivo .= $oParalisacoes->s139_c_descr;
          $sSep     = ', ';
 
        }   
 
      } else { // Paralisado o dia inteiro
 
        $sMotivo = '00:00 - 23:59 - '.$oParalisacoes->s139_c_descr;
        break;
 
      }   
 
    }   
 
    return $sMotivo; 

}
}
?>