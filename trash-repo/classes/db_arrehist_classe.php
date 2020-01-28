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

//MODULO: caixa
//CLASSE DA ENTIDADE arrehist
class cl_arrehist { 
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
   var $k00_numpre = 0; 
   var $k00_numpar = 0; 
   var $k00_hist = 0; 
   var $k00_dtoper_dia = null; 
   var $k00_dtoper_mes = null; 
   var $k00_dtoper_ano = null; 
   var $k00_dtoper = null; 
   var $k00_hora = null; 
   var $k00_id_usuario = 0; 
   var $k00_histtxt = null; 
   var $k00_limithist_dia = null; 
   var $k00_limithist_mes = null; 
   var $k00_limithist_ano = null; 
   var $k00_limithist = null; 
   var $k00_idhist = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k00_numpre = int4 = Numpre 
                 k00_numpar = int4 = Parcela 
                 k00_hist = int4 = Histórico de Cálculo 
                 k00_dtoper = date = DT.Lanc 
                 k00_hora = char(5) = Hora do Cadastro 
                 k00_id_usuario = int4 = Código do Usuário 
                 k00_histtxt = text = Texto para Observação 
                 k00_limithist = date = Data Limite 
                 k00_idhist = int4 = Sequencia 
                 ";
   //funcao construtor da classe 
   function cl_arrehist() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arrehist"); 
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
       $this->k00_numpre = ($this->k00_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpre"]:$this->k00_numpre);
       $this->k00_numpar = ($this->k00_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpar"]:$this->k00_numpar);
       $this->k00_hist = ($this->k00_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist"]:$this->k00_hist);
       if($this->k00_dtoper == ""){
         $this->k00_dtoper_dia = ($this->k00_dtoper_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"]:$this->k00_dtoper_dia);
         $this->k00_dtoper_mes = ($this->k00_dtoper_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_mes"]:$this->k00_dtoper_mes);
         $this->k00_dtoper_ano = ($this->k00_dtoper_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_ano"]:$this->k00_dtoper_ano);
         if($this->k00_dtoper_dia != ""){
            $this->k00_dtoper = $this->k00_dtoper_ano."-".$this->k00_dtoper_mes."-".$this->k00_dtoper_dia;
         }
       }
       $this->k00_hora = ($this->k00_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hora"]:$this->k00_hora);
       $this->k00_id_usuario = ($this->k00_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_id_usuario"]:$this->k00_id_usuario);
       $this->k00_histtxt = ($this->k00_histtxt == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_histtxt"]:$this->k00_histtxt);
       if($this->k00_limithist == ""){
         $this->k00_limithist_dia = ($this->k00_limithist_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_limithist_dia"]:$this->k00_limithist_dia);
         $this->k00_limithist_mes = ($this->k00_limithist_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_limithist_mes"]:$this->k00_limithist_mes);
         $this->k00_limithist_ano = ($this->k00_limithist_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_limithist_ano"]:$this->k00_limithist_ano);
         if($this->k00_limithist_dia != ""){
            $this->k00_limithist = $this->k00_limithist_ano."-".$this->k00_limithist_mes."-".$this->k00_limithist_dia;
         }
       }
       $this->k00_idhist = ($this->k00_idhist == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_idhist"]:$this->k00_idhist);
     }else{
       $this->k00_idhist = ($this->k00_idhist == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_idhist"]:$this->k00_idhist);
     }
   }
   // funcao para inclusao
   function incluir ($k00_idhist){ 
      $this->atualizacampos();
     if($this->k00_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "k00_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numpar == null ){ 
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "k00_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_hist == null ){ 
       $this->erro_sql = " Campo Histórico de Cálculo nao Informado.";
       $this->erro_campo = "k00_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_dtoper == null ){ 
       $this->erro_sql = " Campo DT.Lanc nao Informado.";
       $this->erro_campo = "k00_dtoper_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_hora == null ){ 
       $this->erro_sql = " Campo Hora do Cadastro nao Informado.";
       $this->erro_campo = "k00_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_id_usuario == null ){ 
       $this->erro_sql = " Campo Código do Usuário nao Informado.";
       $this->erro_campo = "k00_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_histtxt == null ){ 
       $this->erro_sql = " Campo Texto para Observação nao Informado.";
       $this->erro_campo = "k00_histtxt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_limithist == null ){ 
       $this->k00_limithist = "null";
     }
     if($k00_idhist == "" || $k00_idhist == null ){
       $result = db_query("select nextval('arrehist_k00_idhist_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: arrehist_k00_idhist_seq do campo: k00_idhist"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k00_idhist = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from arrehist_k00_idhist_seq");
       if(($result != false) && (pg_result($result,0,0) < $k00_idhist)){
         $this->erro_sql = " Campo k00_idhist maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k00_idhist = $k00_idhist; 
       }
     }
     if(($this->k00_idhist == null) || ($this->k00_idhist == "") ){ 
       $this->erro_sql = " Campo k00_idhist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arrehist(
                                       k00_numpre 
                                      ,k00_numpar 
                                      ,k00_hist 
                                      ,k00_dtoper 
                                      ,k00_hora 
                                      ,k00_id_usuario 
                                      ,k00_histtxt 
                                      ,k00_limithist 
                                      ,k00_idhist 
                       )
                values (
                                $this->k00_numpre 
                               ,$this->k00_numpar 
                               ,$this->k00_hist 
                               ,".($this->k00_dtoper == "null" || $this->k00_dtoper == ""?"null":"'".$this->k00_dtoper."'")." 
                               ,'$this->k00_hora' 
                               ,$this->k00_id_usuario 
                               ,'$this->k00_histtxt' 
                               ,".($this->k00_limithist == "null" || $this->k00_limithist == ""?"null":"'".$this->k00_limithist."'")." 
                               ,$this->k00_idhist 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Histórico de Arrecadações ($this->k00_idhist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Histórico de Arrecadações já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Histórico de Arrecadações ($this->k00_idhist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_idhist;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k00_idhist));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6848,'$this->k00_idhist','I')");
       $resac = db_query("insert into db_acount values($acount,411,361,'','".AddSlashes(pg_result($resaco,0,'k00_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,411,362,'','".AddSlashes(pg_result($resaco,0,'k00_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,411,375,'','".AddSlashes(pg_result($resaco,0,'k00_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,411,373,'','".AddSlashes(pg_result($resaco,0,'k00_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,411,2494,'','".AddSlashes(pg_result($resaco,0,'k00_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,411,2495,'','".AddSlashes(pg_result($resaco,0,'k00_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,411,2378,'','".AddSlashes(pg_result($resaco,0,'k00_histtxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,411,6847,'','".AddSlashes(pg_result($resaco,0,'k00_limithist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,411,6848,'','".AddSlashes(pg_result($resaco,0,'k00_idhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k00_idhist=null) { 
      $this->atualizacampos();
     $sql = " update arrehist set ";
     $virgula = "";
     if(trim($this->k00_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"])){ 
       $sql  .= $virgula." k00_numpre = $this->k00_numpre ";
       $virgula = ",";
       if(trim($this->k00_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k00_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpar"])){ 
       $sql  .= $virgula." k00_numpar = $this->k00_numpar ";
       $virgula = ",";
       if(trim($this->k00_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "k00_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist"])){ 
       $sql  .= $virgula." k00_hist = $this->k00_hist ";
       $virgula = ",";
       if(trim($this->k00_hist) == null ){ 
         $this->erro_sql = " Campo Histórico de Cálculo nao Informado.";
         $this->erro_campo = "k00_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"] !="") ){ 
       $sql  .= $virgula." k00_dtoper = '$this->k00_dtoper' ";
       $virgula = ",";
       if(trim($this->k00_dtoper) == null ){ 
         $this->erro_sql = " Campo DT.Lanc nao Informado.";
         $this->erro_campo = "k00_dtoper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"])){ 
         $sql  .= $virgula." k00_dtoper = null ";
         $virgula = ",";
         if(trim($this->k00_dtoper) == null ){ 
           $this->erro_sql = " Campo DT.Lanc nao Informado.";
           $this->erro_campo = "k00_dtoper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k00_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hora"])){ 
       $sql  .= $virgula." k00_hora = '$this->k00_hora' ";
       $virgula = ",";
       if(trim($this->k00_hora) == null ){ 
         $this->erro_sql = " Campo Hora do Cadastro nao Informado.";
         $this->erro_campo = "k00_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_id_usuario"])){ 
       $sql  .= $virgula." k00_id_usuario = $this->k00_id_usuario ";
       $virgula = ",";
       if(trim($this->k00_id_usuario) == null ){ 
         $this->erro_sql = " Campo Código do Usuário nao Informado.";
         $this->erro_campo = "k00_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_histtxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_histtxt"])){ 
       $sql  .= $virgula." k00_histtxt = '$this->k00_histtxt' ";
       $virgula = ",";
       if(trim($this->k00_histtxt) == null ){ 
         $this->erro_sql = " Campo Texto para Observação nao Informado.";
         $this->erro_campo = "k00_histtxt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_limithist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_limithist_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_limithist_dia"] !="") ){ 
       $sql  .= $virgula." k00_limithist = '$this->k00_limithist' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_limithist_dia"])){ 
         $sql  .= $virgula." k00_limithist = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k00_idhist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_idhist"])){ 
       $sql  .= $virgula." k00_idhist = $this->k00_idhist ";
       $virgula = ",";
       if(trim($this->k00_idhist) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "k00_idhist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k00_idhist!=null){
       $sql .= " k00_idhist = $this->k00_idhist";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k00_idhist));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6848,'$this->k00_idhist','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"]))
           $resac = db_query("insert into db_acount values($acount,411,361,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numpre'))."','$this->k00_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numpar"]))
           $resac = db_query("insert into db_acount values($acount,411,362,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numpar'))."','$this->k00_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_hist"]))
           $resac = db_query("insert into db_acount values($acount,411,375,'".AddSlashes(pg_result($resaco,$conresaco,'k00_hist'))."','$this->k00_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper"]))
           $resac = db_query("insert into db_acount values($acount,411,373,'".AddSlashes(pg_result($resaco,$conresaco,'k00_dtoper'))."','$this->k00_dtoper',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_hora"]))
           $resac = db_query("insert into db_acount values($acount,411,2494,'".AddSlashes(pg_result($resaco,$conresaco,'k00_hora'))."','$this->k00_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,411,2495,'".AddSlashes(pg_result($resaco,$conresaco,'k00_id_usuario'))."','$this->k00_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_histtxt"]))
           $resac = db_query("insert into db_acount values($acount,411,2378,'".AddSlashes(pg_result($resaco,$conresaco,'k00_histtxt'))."','$this->k00_histtxt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_limithist"]))
           $resac = db_query("insert into db_acount values($acount,411,6847,'".AddSlashes(pg_result($resaco,$conresaco,'k00_limithist'))."','$this->k00_limithist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_idhist"]))
           $resac = db_query("insert into db_acount values($acount,411,6848,'".AddSlashes(pg_result($resaco,$conresaco,'k00_idhist'))."','$this->k00_idhist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico de Arrecadações nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_idhist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Histórico de Arrecadações nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_idhist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_idhist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k00_idhist=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k00_idhist));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6848,'$k00_idhist','E')");
         $resac = db_query("insert into db_acount values($acount,411,361,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,411,362,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,411,375,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,411,373,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,411,2494,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,411,2495,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,411,2378,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_histtxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,411,6847,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_limithist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,411,6848,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_idhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from arrehist
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k00_idhist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k00_idhist = $k00_idhist ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico de Arrecadações nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k00_idhist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Histórico de Arrecadações nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k00_idhist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k00_idhist;
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
        $this->erro_sql   = "Record Vazio na Tabela:arrehist";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k00_idhist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arrehist ";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = arrehist.k00_hist";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = arrehist.k00_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($k00_idhist!=null ){
         $sql2 .= " where arrehist.k00_idhist = $k00_idhist "; 
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
   function sql_query_file ( $k00_idhist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arrehist ";
     $sql2 = "";
     if($dbwhere==""){
       if($k00_idhist!=null ){
         $sql2 .= " where arrehist.k00_idhist = $k00_idhist "; 
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