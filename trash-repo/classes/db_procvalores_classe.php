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

//MODULO: saude
//CLASSE DA ENTIDADE procvalores
class cl_procvalores { 
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
   var $sd10_i_procedimento = 0; 
   var $sd10_c_sala = 0; 
   var $sd10_f_valor = 0; 
   var $sd10_f_servico = 0; 
   var $sd10_f_anestesia = 0; 
   var $sd10_f_material = 0; 
   var $sd10_f_contraste = 0; 
   var $sd10_f_filme = 0; 
   var $sd10_f_gesso = 0; 
   var $sd10_f_quimio = 0; 
   var $sd10_f_dialise = 0; 
   var $sd10_f_sadtrat = 0; 
   var $sd10_f_sadtpc = 0; 
   var $sd10_f_sadtout = 0; 
   var $sd10_f_outro = 0; 
   var $sd10_f_filme2 = 0; 
   var $sd10_f_total = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd10_i_procedimento = int4 = Cód. Procedimento 
                 sd10_c_sala = float8 = Taxa da sala 
                 sd10_f_valor = float8 = Valor do Procedimento 
                 sd10_f_servico = int8 = Serviço Profissional 
                 sd10_f_anestesia = float8 = Anestesia 
                 sd10_f_material = float8 = Material Médico 
                 sd10_f_contraste = float8 = Contraste 
                 sd10_f_filme = float8 = Filme 
                 sd10_f_gesso = float8 = Gesso 
                 sd10_f_quimio = float8 = Quimioterapia 
                 sd10_f_dialise = float8 = Dialise 
                 sd10_f_sadtrat = float8 = Sadt rat 
                 sd10_f_sadtpc = float8 = Sadt pc 
                 sd10_f_sadtout = float8 = Sadt outros 
                 sd10_f_outro = float8 = Outros 
                 sd10_f_filme2 = float8 = Filme2 
                 sd10_f_total = float8 = Total 
                 ";
   //funcao construtor da classe 
   function cl_procvalores() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procvalores"); 
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
       $this->sd10_i_procedimento = ($this->sd10_i_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_i_procedimento"]:$this->sd10_i_procedimento);
       $this->sd10_c_sala = ($this->sd10_c_sala == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_c_sala"]:$this->sd10_c_sala);
       $this->sd10_f_valor = ($this->sd10_f_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_valor"]:$this->sd10_f_valor);
       $this->sd10_f_servico = ($this->sd10_f_servico == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_servico"]:$this->sd10_f_servico);
       $this->sd10_f_anestesia = ($this->sd10_f_anestesia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_anestesia"]:$this->sd10_f_anestesia);
       $this->sd10_f_material = ($this->sd10_f_material == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_material"]:$this->sd10_f_material);
       $this->sd10_f_contraste = ($this->sd10_f_contraste == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_contraste"]:$this->sd10_f_contraste);
       $this->sd10_f_filme = ($this->sd10_f_filme == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_filme"]:$this->sd10_f_filme);
       $this->sd10_f_gesso = ($this->sd10_f_gesso == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_gesso"]:$this->sd10_f_gesso);
       $this->sd10_f_quimio = ($this->sd10_f_quimio == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_quimio"]:$this->sd10_f_quimio);
       $this->sd10_f_dialise = ($this->sd10_f_dialise == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_dialise"]:$this->sd10_f_dialise);
       $this->sd10_f_sadtrat = ($this->sd10_f_sadtrat == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_sadtrat"]:$this->sd10_f_sadtrat);
       $this->sd10_f_sadtpc = ($this->sd10_f_sadtpc == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_sadtpc"]:$this->sd10_f_sadtpc);
       $this->sd10_f_sadtout = ($this->sd10_f_sadtout == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_sadtout"]:$this->sd10_f_sadtout);
       $this->sd10_f_outro = ($this->sd10_f_outro == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_outro"]:$this->sd10_f_outro);
       $this->sd10_f_filme2 = ($this->sd10_f_filme2 == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_filme2"]:$this->sd10_f_filme2);
       $this->sd10_f_total = ($this->sd10_f_total == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_f_total"]:$this->sd10_f_total);
     }else{
       $this->sd10_i_procedimento = ($this->sd10_i_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd10_i_procedimento"]:$this->sd10_i_procedimento);
     }
   }
   // funcao para inclusao
   function incluir ($sd10_i_procedimento){ 
      $this->atualizacampos();
     if($this->sd10_c_sala == null ){ 
       $this->sd10_c_sala = "0";
     }
     if($this->sd10_f_valor == null ){ 
       $this->sd10_f_valor = "0";
     }
     if($this->sd10_f_servico == null ){ 
       $this->sd10_f_servico = "0";
     }
     if($this->sd10_f_anestesia == null ){ 
       $this->sd10_f_anestesia = "0";
     }
     if($this->sd10_f_material == null ){ 
       $this->sd10_f_material = "0";
     }
     if($this->sd10_f_contraste == null ){ 
       $this->sd10_f_contraste = "0";
     }
     if($this->sd10_f_filme == null ){ 
       $this->sd10_f_filme = "0";
     }
     if($this->sd10_f_gesso == null ){ 
       $this->sd10_f_gesso = "0";
     }
     if($this->sd10_f_quimio == null ){ 
       $this->sd10_f_quimio = "0";
     }
     if($this->sd10_f_dialise == null ){ 
       $this->sd10_f_dialise = "0";
     }
     if($this->sd10_f_sadtrat == null ){ 
       $this->sd10_f_sadtrat = "0";
     }
     if($this->sd10_f_sadtpc == null ){ 
       $this->sd10_f_sadtpc = "0";
     }
     if($this->sd10_f_sadtout == null ){ 
       $this->sd10_f_sadtout = "0";
     }
     if($this->sd10_f_outro == null ){ 
       $this->sd10_f_outro = "0";
     }
     if($this->sd10_f_filme2 == null ){ 
       $this->sd10_f_filme2 = "0";
     }
     if($this->sd10_f_total == null ){ 
       $this->sd10_f_total = "0";
     }
       $this->sd10_i_procedimento = $sd10_i_procedimento; 
     if(($this->sd10_i_procedimento == null) || ($this->sd10_i_procedimento == "") ){ 
       $this->erro_sql = " Campo sd10_i_procedimento nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procvalores(
                                       sd10_i_procedimento 
                                      ,sd10_c_sala 
                                      ,sd10_f_valor 
                                      ,sd10_f_servico 
                                      ,sd10_f_anestesia 
                                      ,sd10_f_material 
                                      ,sd10_f_contraste 
                                      ,sd10_f_filme 
                                      ,sd10_f_gesso 
                                      ,sd10_f_quimio 
                                      ,sd10_f_dialise 
                                      ,sd10_f_sadtrat 
                                      ,sd10_f_sadtpc 
                                      ,sd10_f_sadtout 
                                      ,sd10_f_outro 
                                      ,sd10_f_filme2 
                                      ,sd10_f_total 
                       )
                values (
                                $this->sd10_i_procedimento 
                               ,$this->sd10_c_sala 
                               ,$this->sd10_f_valor 
                               ,$this->sd10_f_servico 
                               ,$this->sd10_f_anestesia 
                               ,$this->sd10_f_material 
                               ,$this->sd10_f_contraste 
                               ,$this->sd10_f_filme 
                               ,$this->sd10_f_gesso 
                               ,$this->sd10_f_quimio 
                               ,$this->sd10_f_dialise 
                               ,$this->sd10_f_sadtrat 
                               ,$this->sd10_f_sadtpc 
                               ,$this->sd10_f_sadtout 
                               ,$this->sd10_f_outro 
                               ,$this->sd10_f_filme2 
                               ,$this->sd10_f_total 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores ($this->sd10_i_procedimento) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores ($this->sd10_i_procedimento) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd10_i_procedimento;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd10_i_procedimento));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,100081,'$this->sd10_i_procedimento','I')");
       $resac = db_query("insert into db_acount values($acount,100005,100081,'','".AddSlashes(pg_result($resaco,0,'sd10_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100082,'','".AddSlashes(pg_result($resaco,0,'sd10_c_sala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100083,'','".AddSlashes(pg_result($resaco,0,'sd10_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100084,'','".AddSlashes(pg_result($resaco,0,'sd10_f_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100085,'','".AddSlashes(pg_result($resaco,0,'sd10_f_anestesia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100086,'','".AddSlashes(pg_result($resaco,0,'sd10_f_material'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100087,'','".AddSlashes(pg_result($resaco,0,'sd10_f_contraste'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100088,'','".AddSlashes(pg_result($resaco,0,'sd10_f_filme'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100089,'','".AddSlashes(pg_result($resaco,0,'sd10_f_gesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100090,'','".AddSlashes(pg_result($resaco,0,'sd10_f_quimio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100091,'','".AddSlashes(pg_result($resaco,0,'sd10_f_dialise'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100092,'','".AddSlashes(pg_result($resaco,0,'sd10_f_sadtrat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100093,'','".AddSlashes(pg_result($resaco,0,'sd10_f_sadtpc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100094,'','".AddSlashes(pg_result($resaco,0,'sd10_f_sadtout'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100095,'','".AddSlashes(pg_result($resaco,0,'sd10_f_outro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100096,'','".AddSlashes(pg_result($resaco,0,'sd10_f_filme2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100005,100097,'','".AddSlashes(pg_result($resaco,0,'sd10_f_total'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd10_i_procedimento=null) { 
      $this->atualizacampos();
     $sql = " update procvalores set ";
     $virgula = "";
     if(trim($this->sd10_i_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_i_procedimento"])){ 
       $sql  .= $virgula." sd10_i_procedimento = $this->sd10_i_procedimento ";
       $virgula = ",";
       if(trim($this->sd10_i_procedimento) == null ){ 
         $this->erro_sql = " Campo Cód. Procedimento nao Informado.";
         $this->erro_campo = "sd10_i_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd10_c_sala)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_c_sala"])){ 
        if(trim($this->sd10_c_sala)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_c_sala"])){ 
           $this->sd10_c_sala = "0" ; 
        } 
       $sql  .= $virgula." sd10_c_sala = $this->sd10_c_sala ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_valor"])){ 
        if(trim($this->sd10_f_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_valor"])){ 
           $this->sd10_f_valor = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_valor = $this->sd10_f_valor ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_servico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_servico"])){ 
        if(trim($this->sd10_f_servico)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_servico"])){ 
           $this->sd10_f_servico = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_servico = $this->sd10_f_servico ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_anestesia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_anestesia"])){ 
        if(trim($this->sd10_f_anestesia)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_anestesia"])){ 
           $this->sd10_f_anestesia = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_anestesia = $this->sd10_f_anestesia ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_material)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_material"])){ 
        if(trim($this->sd10_f_material)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_material"])){ 
           $this->sd10_f_material = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_material = $this->sd10_f_material ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_contraste)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_contraste"])){ 
        if(trim($this->sd10_f_contraste)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_contraste"])){ 
           $this->sd10_f_contraste = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_contraste = $this->sd10_f_contraste ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_filme)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_filme"])){ 
        if(trim($this->sd10_f_filme)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_filme"])){ 
           $this->sd10_f_filme = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_filme = $this->sd10_f_filme ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_gesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_gesso"])){ 
        if(trim($this->sd10_f_gesso)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_gesso"])){ 
           $this->sd10_f_gesso = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_gesso = $this->sd10_f_gesso ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_quimio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_quimio"])){ 
        if(trim($this->sd10_f_quimio)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_quimio"])){ 
           $this->sd10_f_quimio = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_quimio = $this->sd10_f_quimio ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_dialise)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_dialise"])){ 
        if(trim($this->sd10_f_dialise)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_dialise"])){ 
           $this->sd10_f_dialise = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_dialise = $this->sd10_f_dialise ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_sadtrat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_sadtrat"])){ 
        if(trim($this->sd10_f_sadtrat)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_sadtrat"])){ 
           $this->sd10_f_sadtrat = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_sadtrat = $this->sd10_f_sadtrat ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_sadtpc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_sadtpc"])){ 
        if(trim($this->sd10_f_sadtpc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_sadtpc"])){ 
           $this->sd10_f_sadtpc = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_sadtpc = $this->sd10_f_sadtpc ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_sadtout)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_sadtout"])){ 
        if(trim($this->sd10_f_sadtout)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_sadtout"])){ 
           $this->sd10_f_sadtout = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_sadtout = $this->sd10_f_sadtout ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_outro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_outro"])){ 
        if(trim($this->sd10_f_outro)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_outro"])){ 
           $this->sd10_f_outro = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_outro = $this->sd10_f_outro ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_filme2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_filme2"])){ 
        if(trim($this->sd10_f_filme2)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_filme2"])){ 
           $this->sd10_f_filme2 = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_filme2 = $this->sd10_f_filme2 ";
       $virgula = ",";
     }
     if(trim($this->sd10_f_total)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_total"])){ 
        if(trim($this->sd10_f_total)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_total"])){ 
           $this->sd10_f_total = "0" ; 
        } 
       $sql  .= $virgula." sd10_f_total = $this->sd10_f_total ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($sd10_i_procedimento!=null){
       $sql .= " sd10_i_procedimento = $this->sd10_i_procedimento";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd10_i_procedimento));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,100081,'$this->sd10_i_procedimento','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_i_procedimento"]))
           $resac = db_query("insert into db_acount values($acount,100005,100081,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_i_procedimento'))."','$this->sd10_i_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_c_sala"]))
           $resac = db_query("insert into db_acount values($acount,100005,100082,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_c_sala'))."','$this->sd10_c_sala',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_valor"]))
           $resac = db_query("insert into db_acount values($acount,100005,100083,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_valor'))."','$this->sd10_f_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_servico"]))
           $resac = db_query("insert into db_acount values($acount,100005,100084,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_servico'))."','$this->sd10_f_servico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_anestesia"]))
           $resac = db_query("insert into db_acount values($acount,100005,100085,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_anestesia'))."','$this->sd10_f_anestesia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_material"]))
           $resac = db_query("insert into db_acount values($acount,100005,100086,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_material'))."','$this->sd10_f_material',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_contraste"]))
           $resac = db_query("insert into db_acount values($acount,100005,100087,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_contraste'))."','$this->sd10_f_contraste',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_filme"]))
           $resac = db_query("insert into db_acount values($acount,100005,100088,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_filme'))."','$this->sd10_f_filme',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_gesso"]))
           $resac = db_query("insert into db_acount values($acount,100005,100089,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_gesso'))."','$this->sd10_f_gesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_quimio"]))
           $resac = db_query("insert into db_acount values($acount,100005,100090,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_quimio'))."','$this->sd10_f_quimio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_dialise"]))
           $resac = db_query("insert into db_acount values($acount,100005,100091,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_dialise'))."','$this->sd10_f_dialise',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_sadtrat"]))
           $resac = db_query("insert into db_acount values($acount,100005,100092,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_sadtrat'))."','$this->sd10_f_sadtrat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_sadtpc"]))
           $resac = db_query("insert into db_acount values($acount,100005,100093,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_sadtpc'))."','$this->sd10_f_sadtpc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_sadtout"]))
           $resac = db_query("insert into db_acount values($acount,100005,100094,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_sadtout'))."','$this->sd10_f_sadtout',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_outro"]))
           $resac = db_query("insert into db_acount values($acount,100005,100095,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_outro'))."','$this->sd10_f_outro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_filme2"]))
           $resac = db_query("insert into db_acount values($acount,100005,100096,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_filme2'))."','$this->sd10_f_filme2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd10_f_total"]))
           $resac = db_query("insert into db_acount values($acount,100005,100097,'".AddSlashes(pg_result($resaco,$conresaco,'sd10_f_total'))."','$this->sd10_f_total',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd10_i_procedimento;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd10_i_procedimento;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd10_i_procedimento;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd10_i_procedimento=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd10_i_procedimento));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,100081,'$sd10_i_procedimento','E')");
         $resac = db_query("insert into db_acount values($acount,100005,100081,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100082,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_c_sala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100083,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100084,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100085,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_anestesia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100086,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_material'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100087,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_contraste'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100088,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_filme'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100089,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_gesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100090,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_quimio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100091,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_dialise'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100092,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_sadtrat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100093,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_sadtpc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100094,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_sadtout'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100095,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_outro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100096,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_filme2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100005,100097,'','".AddSlashes(pg_result($resaco,$iresaco,'sd10_f_total'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from procvalores
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd10_i_procedimento != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd10_i_procedimento = $sd10_i_procedimento ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd10_i_procedimento;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd10_i_procedimento;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd10_i_procedimento;
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
        $this->erro_sql   = "Record Vazio na Tabela:procvalores";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd10_i_procedimento=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procvalores ";
     $sql .= "      inner join procedimentos  on  procedimentos.sd09_i_codigo = procvalores.sd10_i_procedimento";
     $sql .= "      inner join grupoproc  on  grupoproc.sd11_c_codigo = procedimentos.sd09_c_grupoproc";
     $sql2 = "";
     if($dbwhere==""){
       if($sd10_i_procedimento!=null ){
         $sql2 .= " where procvalores.sd10_i_procedimento = $sd10_i_procedimento "; 
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
   function sql_query_file ( $sd10_i_procedimento=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procvalores ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd10_i_procedimento!=null ){
         $sql2 .= " where procvalores.sd10_i_procedimento = $sd10_i_procedimento "; 
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