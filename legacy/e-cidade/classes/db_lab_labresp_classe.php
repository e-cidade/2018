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

//MODULO: Laboratório
//CLASSE DA ENTIDADE lab_labresp
class cl_lab_labresp { 
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
   var $la06_i_codigo = 0; 
   var $la06_i_uf = 0; 
   var $la06_i_laboratorio = 0; 
   var $la06_i_cgm = 0; 
   var $la06_i_cbo = 0; 
   var $la06_d_inicio_dia = null; 
   var $la06_d_inicio_mes = null; 
   var $la06_d_inicio_ano = null; 
   var $la06_d_inicio = null; 
   var $la06_d_fim_dia = null; 
   var $la06_d_fim_mes = null; 
   var $la06_d_fim_ano = null; 
   var $la06_d_fim = null; 
   var $la06_i_tipo = 0; 
   var $la06_c_orgaoclasse = null; 
   var $la06_c_sigla = null; 
   var $la06_c_cns = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la06_i_codigo = int4 = Código 
                 la06_i_uf = int4 = UF 
                 la06_i_laboratorio = int4 = Laboratório 
                 la06_i_cgm = int4 = CGM 
                 la06_i_cbo = int4 = CBO 
                 la06_d_inicio = date = Início 
                 la06_d_fim = date = Fim 
                 la06_i_tipo = int4 = Tipo 
                 la06_c_orgaoclasse = char(20) = Órgão Classe 
                 la06_c_sigla = char(10) = Sigla 
                 la06_c_cns = char(15) = Cartão SUS 
                 ";
   //funcao construtor da classe 
   function cl_lab_labresp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_labresp"); 
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
       $this->la06_i_codigo = ($this->la06_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_i_codigo"]:$this->la06_i_codigo);
       $this->la06_i_uf = ($this->la06_i_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_i_uf"]:$this->la06_i_uf);
       $this->la06_i_laboratorio = ($this->la06_i_laboratorio == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_i_laboratorio"]:$this->la06_i_laboratorio);
       $this->la06_i_cgm = ($this->la06_i_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_i_cgm"]:$this->la06_i_cgm);
       $this->la06_i_cbo = ($this->la06_i_cbo == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_i_cbo"]:$this->la06_i_cbo);
       if($this->la06_d_inicio == ""){
         $this->la06_d_inicio_dia = ($this->la06_d_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_d_inicio_dia"]:$this->la06_d_inicio_dia);
         $this->la06_d_inicio_mes = ($this->la06_d_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_d_inicio_mes"]:$this->la06_d_inicio_mes);
         $this->la06_d_inicio_ano = ($this->la06_d_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_d_inicio_ano"]:$this->la06_d_inicio_ano);
         if($this->la06_d_inicio_dia != ""){
            $this->la06_d_inicio = $this->la06_d_inicio_ano."-".$this->la06_d_inicio_mes."-".$this->la06_d_inicio_dia;
         }
       }
       if($this->la06_d_fim == ""){
         $this->la06_d_fim_dia = ($this->la06_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_d_fim_dia"]:$this->la06_d_fim_dia);
         $this->la06_d_fim_mes = ($this->la06_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_d_fim_mes"]:$this->la06_d_fim_mes);
         $this->la06_d_fim_ano = ($this->la06_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_d_fim_ano"]:$this->la06_d_fim_ano);
         if($this->la06_d_fim_dia != ""){
            $this->la06_d_fim = $this->la06_d_fim_ano."-".$this->la06_d_fim_mes."-".$this->la06_d_fim_dia;
         }
       }
       $this->la06_i_tipo = ($this->la06_i_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_i_tipo"]:$this->la06_i_tipo);
       $this->la06_c_orgaoclasse = ($this->la06_c_orgaoclasse == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_c_orgaoclasse"]:$this->la06_c_orgaoclasse);
       $this->la06_c_sigla = ($this->la06_c_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_c_sigla"]:$this->la06_c_sigla);
       $this->la06_c_cns = ($this->la06_c_cns == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_c_cns"]:$this->la06_c_cns);
     }else{
       $this->la06_i_codigo = ($this->la06_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la06_i_codigo"]:$this->la06_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la06_i_codigo){ 
      $this->atualizacampos();
     if($this->la06_i_uf == null ){ 
       $this->la06_i_uf = "null";
     }
     if($this->la06_i_laboratorio == null ){ 
       $this->erro_sql = " Campo Laboratório não informado.";
       $this->erro_campo = "la06_i_laboratorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la06_i_cgm == null ){ 
       $this->erro_sql = " Campo CGM não informado.";
       $this->erro_campo = "la06_i_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la06_i_cbo == null ){ 
       $this->erro_sql = " Campo CBO não informado.";
       $this->erro_campo = "la06_i_cbo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la06_d_inicio == null ){ 
       $this->erro_sql = " Campo Início não informado.";
       $this->erro_campo = "la06_d_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la06_d_fim == null ){ 
       $this->la06_d_fim = "null";
     }
     if($this->la06_i_tipo == null ){ 
       $this->erro_sql = " Campo Tipo não informado.";
       $this->erro_campo = "la06_i_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la06_c_cns == null ){ 
       $this->erro_sql = " Campo Cartão SUS não informado.";
       $this->erro_campo = "la06_c_cns";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la06_i_codigo == "" || $la06_i_codigo == null ){
       $result = db_query("select nextval('lab_labresp_la06_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_labresp_la06_i_codigo_seq do campo: la06_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la06_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_labresp_la06_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la06_i_codigo)){
         $this->erro_sql = " Campo la06_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la06_i_codigo = $la06_i_codigo; 
       }
     }
     if(($this->la06_i_codigo == null) || ($this->la06_i_codigo == "") ){ 
       $this->erro_sql = " Campo la06_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_labresp(
                                       la06_i_codigo 
                                      ,la06_i_uf 
                                      ,la06_i_laboratorio 
                                      ,la06_i_cgm 
                                      ,la06_i_cbo 
                                      ,la06_d_inicio 
                                      ,la06_d_fim 
                                      ,la06_i_tipo 
                                      ,la06_c_orgaoclasse 
                                      ,la06_c_sigla 
                                      ,la06_c_cns 
                       )
                values (
                                $this->la06_i_codigo 
                               ,$this->la06_i_uf 
                               ,$this->la06_i_laboratorio 
                               ,$this->la06_i_cgm 
                               ,$this->la06_i_cbo 
                               ,".($this->la06_d_inicio == "null" || $this->la06_d_inicio == ""?"null":"'".$this->la06_d_inicio."'")." 
                               ,".($this->la06_d_fim == "null" || $this->la06_d_fim == ""?"null":"'".$this->la06_d_fim."'")." 
                               ,$this->la06_i_tipo 
                               ,'$this->la06_c_orgaoclasse' 
                               ,'$this->la06_c_sigla' 
                               ,'$this->la06_c_cns' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lab_labresp ($this->la06_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lab_labresp já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lab_labresp ($this->la06_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la06_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la06_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15723,'$this->la06_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2772,15723,'','".AddSlashes(pg_result($resaco,0,'la06_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2772,15724,'','".AddSlashes(pg_result($resaco,0,'la06_i_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2772,15725,'','".AddSlashes(pg_result($resaco,0,'la06_i_laboratorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2772,15726,'','".AddSlashes(pg_result($resaco,0,'la06_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2772,15727,'','".AddSlashes(pg_result($resaco,0,'la06_i_cbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2772,15728,'','".AddSlashes(pg_result($resaco,0,'la06_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2772,15729,'','".AddSlashes(pg_result($resaco,0,'la06_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2772,15730,'','".AddSlashes(pg_result($resaco,0,'la06_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2772,15731,'','".AddSlashes(pg_result($resaco,0,'la06_c_orgaoclasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2772,15997,'','".AddSlashes(pg_result($resaco,0,'la06_c_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2772,16768,'','".AddSlashes(pg_result($resaco,0,'la06_c_cns'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($la06_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_labresp set ";
     $virgula = "";
     if(trim($this->la06_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la06_i_codigo"])){ 
       $sql  .= $virgula." la06_i_codigo = $this->la06_i_codigo ";
       $virgula = ",";
       if(trim($this->la06_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "la06_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la06_i_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la06_i_uf"])){ 
        if(trim($this->la06_i_uf)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la06_i_uf"])){ 
           $this->la06_i_uf = "null" ; 
        } 
       $sql  .= $virgula." la06_i_uf = $this->la06_i_uf ";
       $virgula = ",";
     }
     if(trim($this->la06_i_laboratorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la06_i_laboratorio"])){ 
       $sql  .= $virgula." la06_i_laboratorio = $this->la06_i_laboratorio ";
       $virgula = ",";
       if(trim($this->la06_i_laboratorio) == null ){ 
         $this->erro_sql = " Campo Laboratório não informado.";
         $this->erro_campo = "la06_i_laboratorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la06_i_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la06_i_cgm"])){ 
       $sql  .= $virgula." la06_i_cgm = $this->la06_i_cgm ";
       $virgula = ",";
       if(trim($this->la06_i_cgm) == null ){ 
         $this->erro_sql = " Campo CGM não informado.";
         $this->erro_campo = "la06_i_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la06_i_cbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la06_i_cbo"])){ 
       $sql  .= $virgula." la06_i_cbo = $this->la06_i_cbo ";
       $virgula = ",";
       if(trim($this->la06_i_cbo) == null ){ 
         $this->erro_sql = " Campo CBO não informado.";
         $this->erro_campo = "la06_i_cbo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la06_d_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la06_d_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la06_d_inicio_dia"] !="") ){ 
       $sql  .= $virgula." la06_d_inicio = '$this->la06_d_inicio' ";
       $virgula = ",";
       if(trim($this->la06_d_inicio) == null ){ 
         $this->erro_sql = " Campo Início não informado.";
         $this->erro_campo = "la06_d_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la06_d_inicio_dia"])){ 
         $sql  .= $virgula." la06_d_inicio = null ";
         $virgula = ",";
         if(trim($this->la06_d_inicio) == null ){ 
           $this->erro_sql = " Campo Início não informado.";
           $this->erro_campo = "la06_d_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la06_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la06_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la06_d_fim_dia"] !="") ){ 
       $sql  .= $virgula." la06_d_fim = '$this->la06_d_fim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la06_d_fim_dia"])){ 
         $sql  .= $virgula." la06_d_fim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->la06_i_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la06_i_tipo"])){ 
       $sql  .= $virgula." la06_i_tipo = $this->la06_i_tipo ";
       $virgula = ",";
       if(trim($this->la06_i_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo não informado.";
         $this->erro_campo = "la06_i_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la06_c_orgaoclasse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la06_c_orgaoclasse"])){ 
       $sql  .= $virgula." la06_c_orgaoclasse = '$this->la06_c_orgaoclasse' ";
       $virgula = ",";
     }
     if(trim($this->la06_c_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la06_c_sigla"])){ 
       $sql  .= $virgula." la06_c_sigla = '$this->la06_c_sigla' ";
       $virgula = ",";
     }
     if(trim($this->la06_c_cns)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la06_c_cns"])){ 
       $sql  .= $virgula." la06_c_cns = '$this->la06_c_cns' ";
       $virgula = ",";
       if(trim($this->la06_c_cns) == null ){ 
         $this->erro_sql = " Campo Cartão SUS não informado.";
         $this->erro_campo = "la06_c_cns";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la06_i_codigo!=null){
       $sql .= " la06_i_codigo = $this->la06_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la06_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,15723,'$this->la06_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la06_i_codigo"]) || $this->la06_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2772,15723,'".AddSlashes(pg_result($resaco,$conresaco,'la06_i_codigo'))."','$this->la06_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la06_i_uf"]) || $this->la06_i_uf != "")
             $resac = db_query("insert into db_acount values($acount,2772,15724,'".AddSlashes(pg_result($resaco,$conresaco,'la06_i_uf'))."','$this->la06_i_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la06_i_laboratorio"]) || $this->la06_i_laboratorio != "")
             $resac = db_query("insert into db_acount values($acount,2772,15725,'".AddSlashes(pg_result($resaco,$conresaco,'la06_i_laboratorio'))."','$this->la06_i_laboratorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la06_i_cgm"]) || $this->la06_i_cgm != "")
             $resac = db_query("insert into db_acount values($acount,2772,15726,'".AddSlashes(pg_result($resaco,$conresaco,'la06_i_cgm'))."','$this->la06_i_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la06_i_cbo"]) || $this->la06_i_cbo != "")
             $resac = db_query("insert into db_acount values($acount,2772,15727,'".AddSlashes(pg_result($resaco,$conresaco,'la06_i_cbo'))."','$this->la06_i_cbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la06_d_inicio"]) || $this->la06_d_inicio != "")
             $resac = db_query("insert into db_acount values($acount,2772,15728,'".AddSlashes(pg_result($resaco,$conresaco,'la06_d_inicio'))."','$this->la06_d_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la06_d_fim"]) || $this->la06_d_fim != "")
             $resac = db_query("insert into db_acount values($acount,2772,15729,'".AddSlashes(pg_result($resaco,$conresaco,'la06_d_fim'))."','$this->la06_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la06_i_tipo"]) || $this->la06_i_tipo != "")
             $resac = db_query("insert into db_acount values($acount,2772,15730,'".AddSlashes(pg_result($resaco,$conresaco,'la06_i_tipo'))."','$this->la06_i_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la06_c_orgaoclasse"]) || $this->la06_c_orgaoclasse != "")
             $resac = db_query("insert into db_acount values($acount,2772,15731,'".AddSlashes(pg_result($resaco,$conresaco,'la06_c_orgaoclasse'))."','$this->la06_c_orgaoclasse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la06_c_sigla"]) || $this->la06_c_sigla != "")
             $resac = db_query("insert into db_acount values($acount,2772,15997,'".AddSlashes(pg_result($resaco,$conresaco,'la06_c_sigla'))."','$this->la06_c_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la06_c_cns"]) || $this->la06_c_cns != "")
             $resac = db_query("insert into db_acount values($acount,2772,16768,'".AddSlashes(pg_result($resaco,$conresaco,'la06_c_cns'))."','$this->la06_c_cns',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_labresp nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la06_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "lab_labresp nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la06_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la06_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($la06_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($la06_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,15723,'$la06_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2772,15723,'','".AddSlashes(pg_result($resaco,$iresaco,'la06_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2772,15724,'','".AddSlashes(pg_result($resaco,$iresaco,'la06_i_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2772,15725,'','".AddSlashes(pg_result($resaco,$iresaco,'la06_i_laboratorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2772,15726,'','".AddSlashes(pg_result($resaco,$iresaco,'la06_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2772,15727,'','".AddSlashes(pg_result($resaco,$iresaco,'la06_i_cbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2772,15728,'','".AddSlashes(pg_result($resaco,$iresaco,'la06_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2772,15729,'','".AddSlashes(pg_result($resaco,$iresaco,'la06_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2772,15730,'','".AddSlashes(pg_result($resaco,$iresaco,'la06_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2772,15731,'','".AddSlashes(pg_result($resaco,$iresaco,'la06_c_orgaoclasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2772,15997,'','".AddSlashes(pg_result($resaco,$iresaco,'la06_c_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2772,16768,'','".AddSlashes(pg_result($resaco,$iresaco,'la06_c_cns'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from lab_labresp
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($la06_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " la06_i_codigo = $la06_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_labresp nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la06_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "lab_labresp nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la06_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la06_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:lab_labresp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($la06_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= " from lab_labresp ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = lab_labresp.la06_i_cgm";
     $sql .= "      left  join db_uf  on  db_uf.db12_codigo = lab_labresp.la06_i_uf";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = lab_labresp.la06_i_cbo";
     $sql .= "      inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_labresp.la06_i_laboratorio";
     $sql .= "      left  join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = lab_laboratorio.la02_i_turnoatend";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la06_i_codigo)) {
         $sql2 .= " where lab_labresp.la06_i_codigo = $la06_i_codigo "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($la06_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from lab_labresp ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la06_i_codigo)){
         $sql2 .= " where lab_labresp.la06_i_codigo = $la06_i_codigo "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

   // funcao do sql 
   function sql_query_responsavel ( $la06_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_labresp ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = lab_labresp.la06_i_cgm";
     $sql .= "      inner join db_usuacgm  on db_usuacgm.cgmlogin = cgm.z01_numcgm ";
     $sql .= "      left  join db_uf  on  db_uf.db12_codigo = lab_labresp.la06_i_uf";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = lab_labresp.la06_i_cbo";
     $sql .= "      inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_labresp.la06_i_laboratorio";
     $sql .= "      inner join lab_labdepart    on  lab_labdepart.la03_i_laboratorio = lab_labresp.la06_i_laboratorio";
     $sql2 = "";
     if($dbwhere==""){
       if($la06_i_codigo!=null ){
         $sql2 .= " where lab_labresp.la06_i_codigo = $la06_i_codigo "; 
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
  function sql_query_setor ( $la06_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

    $sql = "select ";

    if ( $campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for ( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
}

    $sql .= " from lab_labresp ";
    $sql .= "      inner join cgm          on  cgm.z01_numcgm          = lab_labresp.la06_i_cgm";
    $sql .= "      inner join db_usuacgm   on db_usuacgm.cgmlogin      = cgm.z01_numcgm ";
    $sql .= "      left  join lab_labsetor on lab_labsetor.la24_i_resp = lab_labresp.la06_i_codigo";
    $sql2 = "";

    if ( $dbwhere == "" ) {

      if( $la24_i_codigo != null ) {
        $sql2 .= " where lab_labresp.la06_i_codigo = $la06_i_codigo ";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;
    if( $ordem != null ) {

      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }

    return $sql;
  }
}
?>