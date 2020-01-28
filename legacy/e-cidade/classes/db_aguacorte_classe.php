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

//MODULO: Agua
//CLASSE DA ENTIDADE aguacorte
class cl_aguacorte { 
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
   var $x40_codcorte = 0; 
   var $x40_dtinc_dia = null; 
   var $x40_dtinc_mes = null; 
   var $x40_dtinc_ano = null; 
   var $x40_dtinc = null; 
   var $x40_usuario = 0; 
   var $x40_anoini = 0; 
   var $x40_anofim = 0; 
   var $x40_entrega = 0; 
   var $x40_rua = 0; 
   var $x40_vlrminimo = 0; 
   var $x40_sql = null; 
   var $x40_codsituacao = 0; 
   var $x40_zona = 0; 
   var $x40_tipomatricula = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x40_codcorte = int4 = Corte 
                 x40_dtinc = date = Geração 
                 x40_usuario = int4 = Usuário 
                 x40_anoini = int4 = Ano Inicio 
                 x40_anofim = int4 = Ano Fim 
                 x40_entrega = int4 = Zona Entrega 
                 x40_rua = int4 = Logradouro 
                 x40_vlrminimo = float8 = Valor Minimo 
                 x40_sql = text = SQL 
                 x40_codsituacao = int4 = Situação Inicial 
                 x40_zona = int4 = Zona Fiscal 
                 x40_tipomatricula = int4 = Considerar matriculas 
                 ";
   //funcao construtor da classe 
   function cl_aguacorte() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacorte"); 
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
       $this->x40_codcorte = ($this->x40_codcorte == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_codcorte"]:$this->x40_codcorte);
       if($this->x40_dtinc == ""){
         $this->x40_dtinc_dia = ($this->x40_dtinc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_dtinc_dia"]:$this->x40_dtinc_dia);
         $this->x40_dtinc_mes = ($this->x40_dtinc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_dtinc_mes"]:$this->x40_dtinc_mes);
         $this->x40_dtinc_ano = ($this->x40_dtinc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_dtinc_ano"]:$this->x40_dtinc_ano);
         if($this->x40_dtinc_dia != ""){
            $this->x40_dtinc = $this->x40_dtinc_ano."-".$this->x40_dtinc_mes."-".$this->x40_dtinc_dia;
         }
       }
       $this->x40_usuario = ($this->x40_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_usuario"]:$this->x40_usuario);
       $this->x40_anoini = ($this->x40_anoini == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_anoini"]:$this->x40_anoini);
       $this->x40_anofim = ($this->x40_anofim == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_anofim"]:$this->x40_anofim);
       $this->x40_entrega = ($this->x40_entrega == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_entrega"]:$this->x40_entrega);
       $this->x40_rua = ($this->x40_rua == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_rua"]:$this->x40_rua);
       $this->x40_vlrminimo = ($this->x40_vlrminimo == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_vlrminimo"]:$this->x40_vlrminimo);
       $this->x40_sql = ($this->x40_sql == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_sql"]:$this->x40_sql);
       $this->x40_codsituacao = ($this->x40_codsituacao == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_codsituacao"]:$this->x40_codsituacao);
       $this->x40_zona = ($this->x40_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_zona"]:$this->x40_zona);
       $this->x40_tipomatricula = ($this->x40_tipomatricula == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_tipomatricula"]:$this->x40_tipomatricula);
     }else{
       $this->x40_codcorte = ($this->x40_codcorte == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_codcorte"]:$this->x40_codcorte);
     }
   }
   // funcao para inclusao
   function incluir ($x40_codcorte){ 
      $this->atualizacampos();
     if($this->x40_dtinc == null ){ 
       $this->erro_sql = " Campo Geração nao Informado.";
       $this->erro_campo = "x40_dtinc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x40_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "x40_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x40_anoini == null ){ 
       $this->x40_anoini = "0";
     }
     if($this->x40_anofim == null ){ 
       $this->x40_anofim = "0";
     }
     if($this->x40_entrega == null ){ 
       $this->x40_entrega = "0";
     }
     if($this->x40_rua == null ){ 
       $this->x40_rua = "0";
     }
     if($this->x40_vlrminimo == null ){ 
       $this->x40_vlrminimo = "0";
     }
     if($this->x40_codsituacao == null ){ 
       $this->erro_sql = " Campo Situação Inicial nao Informado.";
       $this->erro_campo = "x40_codsituacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x40_zona == null ){ 
       $this->x40_zona = "0";
     }
     if($this->x40_tipomatricula == null ){ 
       $this->erro_sql = " Campo Considerar matriculas nao Informado.";
       $this->erro_campo = "x40_tipomatricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x40_codcorte == "" || $x40_codcorte == null ){
       $result = db_query("select nextval('aguacorte_x40_codcorte_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacorte_x40_codcorte_seq do campo: x40_codcorte"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x40_codcorte = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguacorte_x40_codcorte_seq");
       if(($result != false) && (pg_result($result,0,0) < $x40_codcorte)){
         $this->erro_sql = " Campo x40_codcorte maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x40_codcorte = $x40_codcorte; 
       }
     }
     if(($this->x40_codcorte == null) || ($this->x40_codcorte == "") ){ 
       $this->erro_sql = " Campo x40_codcorte nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacorte(
                                       x40_codcorte 
                                      ,x40_dtinc 
                                      ,x40_usuario 
                                      ,x40_anoini 
                                      ,x40_anofim 
                                      ,x40_entrega 
                                      ,x40_rua 
                                      ,x40_vlrminimo 
                                      ,x40_sql 
                                      ,x40_codsituacao 
                                      ,x40_zona 
                                      ,x40_tipomatricula 
                       )
                values (
                                $this->x40_codcorte 
                               ,".($this->x40_dtinc == "null" || $this->x40_dtinc == ""?"null":"'".$this->x40_dtinc."'")." 
                               ,$this->x40_usuario 
                               ,$this->x40_anoini 
                               ,$this->x40_anofim 
                               ,$this->x40_entrega 
                               ,$this->x40_rua 
                               ,$this->x40_vlrminimo 
                               ,'$this->x40_sql' 
                               ,$this->x40_codsituacao 
                               ,$this->x40_zona 
                               ,$this->x40_tipomatricula 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguacorte ($this->x40_codcorte) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguacorte já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguacorte ($this->x40_codcorte) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x40_codcorte;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x40_codcorte));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8520,'$this->x40_codcorte','I')");
       $resac = db_query("insert into db_acount values($acount,1452,8520,'','".AddSlashes(pg_result($resaco,0,'x40_codcorte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1452,8521,'','".AddSlashes(pg_result($resaco,0,'x40_dtinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1452,8522,'','".AddSlashes(pg_result($resaco,0,'x40_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1452,8523,'','".AddSlashes(pg_result($resaco,0,'x40_anoini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1452,8535,'','".AddSlashes(pg_result($resaco,0,'x40_anofim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1452,8536,'','".AddSlashes(pg_result($resaco,0,'x40_entrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1452,8537,'','".AddSlashes(pg_result($resaco,0,'x40_rua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1452,8538,'','".AddSlashes(pg_result($resaco,0,'x40_vlrminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1452,8544,'','".AddSlashes(pg_result($resaco,0,'x40_sql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1452,8570,'','".AddSlashes(pg_result($resaco,0,'x40_codsituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1452,9611,'','".AddSlashes(pg_result($resaco,0,'x40_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1452,14285,'','".AddSlashes(pg_result($resaco,0,'x40_tipomatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x40_codcorte=null) { 
      $this->atualizacampos();
     $sql = " update aguacorte set ";
     $virgula = "";
     if(trim($this->x40_codcorte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x40_codcorte"])){ 
       $sql  .= $virgula." x40_codcorte = $this->x40_codcorte ";
       $virgula = ",";
       if(trim($this->x40_codcorte) == null ){ 
         $this->erro_sql = " Campo Corte nao Informado.";
         $this->erro_campo = "x40_codcorte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x40_dtinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x40_dtinc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x40_dtinc_dia"] !="") ){ 
       $sql  .= $virgula." x40_dtinc = '$this->x40_dtinc' ";
       $virgula = ",";
       if(trim($this->x40_dtinc) == null ){ 
         $this->erro_sql = " Campo Geração nao Informado.";
         $this->erro_campo = "x40_dtinc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x40_dtinc_dia"])){ 
         $sql  .= $virgula." x40_dtinc = null ";
         $virgula = ",";
         if(trim($this->x40_dtinc) == null ){ 
           $this->erro_sql = " Campo Geração nao Informado.";
           $this->erro_campo = "x40_dtinc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x40_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x40_usuario"])){ 
       $sql  .= $virgula." x40_usuario = $this->x40_usuario ";
       $virgula = ",";
       if(trim($this->x40_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "x40_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x40_anoini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x40_anoini"])){ 
        if(trim($this->x40_anoini)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x40_anoini"])){ 
           $this->x40_anoini = "0" ; 
        } 
       $sql  .= $virgula." x40_anoini = $this->x40_anoini ";
       $virgula = ",";
     }
     if(trim($this->x40_anofim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x40_anofim"])){ 
        if(trim($this->x40_anofim)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x40_anofim"])){ 
           $this->x40_anofim = "0" ; 
        } 
       $sql  .= $virgula." x40_anofim = $this->x40_anofim ";
       $virgula = ",";
     }
     if(trim($this->x40_entrega)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x40_entrega"])){ 
        if(trim($this->x40_entrega)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x40_entrega"])){ 
           $this->x40_entrega = "0" ; 
        } 
       $sql  .= $virgula." x40_entrega = $this->x40_entrega ";
       $virgula = ",";
     }
     if(trim($this->x40_rua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x40_rua"])){ 
        if(trim($this->x40_rua)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x40_rua"])){ 
           $this->x40_rua = "0" ; 
        } 
       $sql  .= $virgula." x40_rua = $this->x40_rua ";
       $virgula = ",";
     }
     if(trim($this->x40_vlrminimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x40_vlrminimo"])){ 
        if(trim($this->x40_vlrminimo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x40_vlrminimo"])){ 
           $this->x40_vlrminimo = "0" ; 
        } 
       $sql  .= $virgula." x40_vlrminimo = $this->x40_vlrminimo ";
       $virgula = ",";
     }
     if(trim($this->x40_sql)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x40_sql"])){ 
       $sql  .= $virgula." x40_sql = '$this->x40_sql' ";
       $virgula = ",";
     }
     if(trim($this->x40_codsituacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x40_codsituacao"])){ 
       $sql  .= $virgula." x40_codsituacao = $this->x40_codsituacao ";
       $virgula = ",";
       if(trim($this->x40_codsituacao) == null ){ 
         $this->erro_sql = " Campo Situação Inicial nao Informado.";
         $this->erro_campo = "x40_codsituacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x40_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x40_zona"])){ 
        if(trim($this->x40_zona)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x40_zona"])){ 
           $this->x40_zona = "0" ; 
        } 
       $sql  .= $virgula." x40_zona = $this->x40_zona ";
       $virgula = ",";
     }
     if(trim($this->x40_tipomatricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x40_tipomatricula"])){ 
       $sql  .= $virgula." x40_tipomatricula = $this->x40_tipomatricula ";
       $virgula = ",";
       if(trim($this->x40_tipomatricula) == null ){ 
         $this->erro_sql = " Campo Considerar matriculas nao Informado.";
         $this->erro_campo = "x40_tipomatricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x40_codcorte!=null){
       $sql .= " x40_codcorte = $this->x40_codcorte";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x40_codcorte));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8520,'$this->x40_codcorte','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x40_codcorte"]) || $this->x40_codcorte != "")
           $resac = db_query("insert into db_acount values($acount,1452,8520,'".AddSlashes(pg_result($resaco,$conresaco,'x40_codcorte'))."','$this->x40_codcorte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x40_dtinc"]) || $this->x40_dtinc != "")
           $resac = db_query("insert into db_acount values($acount,1452,8521,'".AddSlashes(pg_result($resaco,$conresaco,'x40_dtinc'))."','$this->x40_dtinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x40_usuario"]) || $this->x40_usuario != "")
           $resac = db_query("insert into db_acount values($acount,1452,8522,'".AddSlashes(pg_result($resaco,$conresaco,'x40_usuario'))."','$this->x40_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x40_anoini"]) || $this->x40_anoini != "")
           $resac = db_query("insert into db_acount values($acount,1452,8523,'".AddSlashes(pg_result($resaco,$conresaco,'x40_anoini'))."','$this->x40_anoini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x40_anofim"]) || $this->x40_anofim != "")
           $resac = db_query("insert into db_acount values($acount,1452,8535,'".AddSlashes(pg_result($resaco,$conresaco,'x40_anofim'))."','$this->x40_anofim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x40_entrega"]) || $this->x40_entrega != "")
           $resac = db_query("insert into db_acount values($acount,1452,8536,'".AddSlashes(pg_result($resaco,$conresaco,'x40_entrega'))."','$this->x40_entrega',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x40_rua"]) || $this->x40_rua != "")
           $resac = db_query("insert into db_acount values($acount,1452,8537,'".AddSlashes(pg_result($resaco,$conresaco,'x40_rua'))."','$this->x40_rua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x40_vlrminimo"]) || $this->x40_vlrminimo != "")
           $resac = db_query("insert into db_acount values($acount,1452,8538,'".AddSlashes(pg_result($resaco,$conresaco,'x40_vlrminimo'))."','$this->x40_vlrminimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x40_sql"]) || $this->x40_sql != "")
           $resac = db_query("insert into db_acount values($acount,1452,8544,'".AddSlashes(pg_result($resaco,$conresaco,'x40_sql'))."','$this->x40_sql',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x40_codsituacao"]) || $this->x40_codsituacao != "")
           $resac = db_query("insert into db_acount values($acount,1452,8570,'".AddSlashes(pg_result($resaco,$conresaco,'x40_codsituacao'))."','$this->x40_codsituacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x40_zona"]) || $this->x40_zona != "")
           $resac = db_query("insert into db_acount values($acount,1452,9611,'".AddSlashes(pg_result($resaco,$conresaco,'x40_zona'))."','$this->x40_zona',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x40_tipomatricula"]) || $this->x40_tipomatricula != "")
           $resac = db_query("insert into db_acount values($acount,1452,14285,'".AddSlashes(pg_result($resaco,$conresaco,'x40_tipomatricula'))."','$this->x40_tipomatricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacorte nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x40_codcorte;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacorte nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x40_codcorte;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x40_codcorte;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x40_codcorte=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x40_codcorte));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8520,'$x40_codcorte','E')");
         $resac = db_query("insert into db_acount values($acount,1452,8520,'','".AddSlashes(pg_result($resaco,$iresaco,'x40_codcorte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1452,8521,'','".AddSlashes(pg_result($resaco,$iresaco,'x40_dtinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1452,8522,'','".AddSlashes(pg_result($resaco,$iresaco,'x40_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1452,8523,'','".AddSlashes(pg_result($resaco,$iresaco,'x40_anoini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1452,8535,'','".AddSlashes(pg_result($resaco,$iresaco,'x40_anofim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1452,8536,'','".AddSlashes(pg_result($resaco,$iresaco,'x40_entrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1452,8537,'','".AddSlashes(pg_result($resaco,$iresaco,'x40_rua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1452,8538,'','".AddSlashes(pg_result($resaco,$iresaco,'x40_vlrminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1452,8544,'','".AddSlashes(pg_result($resaco,$iresaco,'x40_sql'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1452,8570,'','".AddSlashes(pg_result($resaco,$iresaco,'x40_codsituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1452,9611,'','".AddSlashes(pg_result($resaco,$iresaco,'x40_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1452,14285,'','".AddSlashes(pg_result($resaco,$iresaco,'x40_tipomatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguacorte
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x40_codcorte != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x40_codcorte = $x40_codcorte ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacorte nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x40_codcorte;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacorte nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x40_codcorte;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x40_codcorte;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguacorte";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $x40_codcorte=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacorte ";
     $sql .= "      left  join ruas  on  ruas.j14_codigo = aguacorte.x40_rua";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = aguacorte.x40_usuario";
     $sql .= "      left  join zonas  on  zonas.j50_zona = aguacorte.x40_zona";
     $sql .= "      left  join iptucadzonaentrega  on  iptucadzonaentrega.j85_codigo = aguacorte.x40_entrega";
     $sql .= "      inner join aguacortesituacao  on  aguacortesituacao.x43_codsituacao = aguacorte.x40_codsituacao";
     $sql2 = "";
     if($dbwhere==""){
       if($x40_codcorte!=null ){
         $sql2 .= " where aguacorte.x40_codcorte = $x40_codcorte "; 
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
   function sql_query_file ( $x40_codcorte=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacorte ";
     $sql2 = "";
     if($dbwhere==""){
       if($x40_codcorte!=null ){
         $sql2 .= " where aguacorte.x40_codcorte = $x40_codcorte "; 
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
  function sql_query_listapag ( $codcorte ) {
    $sql = "
      select x41_codcortemat,
             x41_matric,
             x45_parcelas,
             k00_tipo, 
             k00_descr,
             count(distinct x99_parcelas) as x99_parcelas,
             sum(x99_arrecad)             as x99_arrecad,
             sum(x99_recibopaga)          as x99_recibopaga,
             sum(x99_parcelas_pagas)      as x99_parcelas_pagas
          from (
               select distinct
                      x41_codcortemat,
                      x41_matric,
                      x45_parcelas,
                      p.k00_tipo, 
                      p.k00_descr, 
                      case when k03_tipo <> 5 then x44_numpre else 0 end as x44_numpre,
                      x44_numpar as x99_parcelas,
                      case when a.k00_numpre is not null then 1 else 0 end as x99_arrecad,
                      case when r.k00_numpar is not null and a.k00_numpre is not null then 1 else 0 end as x99_recibopaga,
                      case when a.k00_numpre is null     then 1 else 0 end as x99_parcelas_pagas
                 from aguacorte
                      inner join aguacortemat        on x41_codcorte = x40_codcorte
                      inner join aguacortetipodebito on x45_codcorte = x40_codcorte

                      inner join aguacortematnumpre  on x44_codcortemat = x41_codcortemat
                                                    and x44_tipo = x45_tipo

                      inner join arretipo p          on p.k00_tipo = x45_tipo

                      left join arrecad a            on a.k00_numpre = x44_numpre
                                                    and a.k00_numpar = x44_numpar
                                                    and a.k00_receit = x44_receit
                                                    
                      left join recibopaga r         on r.k00_numpre = x44_numpre
                                                    and r.k00_numpar = x44_numpar
                                                    and r.k00_receit = x44_receit
                                                    and cast(r.k00_dtoper + interval '1 day' as date) >= '".date("Y-m-d", db_getsession("DB_datausu"))."' 

                where x41_codcorte = {$codcorte} ) as x
         group by x41_codcortemat, x41_matric, x45_parcelas, k00_tipo, k00_descr ;
    ";

    return $sql; 
  }
   function sql_query_recibopaga ( $codcortemat, $dias=1 ) {
    $sql = "
      select distinct
             r.k00_dtoper            as x99_dtoper,
             r.k00_numnov            as x99_numnov
        from aguacortemat
             inner join aguacortetipodebito  on x45_codcorte    = x41_codcorte
             inner join aguacortematnumpre   on x44_codcortemat = x41_codcortemat
                                            and x44_tipo        = x45_tipo

             inner join recibopaga r         on r.k00_numpre    = x44_numpre
                                            and r.k00_numpar    = x44_numpar
                                            and r.k00_receit    = x44_receit
                                            and cast(r.k00_dtoper + interval '{$dias} day' as date) >= '".date("Y-m-d", db_getsession("DB_datausu"))."'
       where x41_codcortemat = {$codcortemat}
    ";
    //die($sql);
    return $sql;
  }
}
?>